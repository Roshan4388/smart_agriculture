// Map module JS
let map;
let ownerBoundary;
let markers = [];
let currentMapType = 'leaflet';
let selectedCropFilter = 'all';
let weatherData = {};

const cropColors = {
    'Rice': '#2E7D32',
    'Wheat': '#F4D03F',
    'Corn': '#F0AD4E',
    'Vegetables': '#27AE60',
    'Fruits': '#E74C3C',
    'Pulses': '#9B59B6',
    'all': '#3498DB'
};

const pesticidesLevel = {
    'normal': { color: '#27AE60', icon: '✓' },
    'warning': { color: '#F39C12', icon: '⚠' },
    'danger': { color: '#E74C3C', icon: '✕' }
};

function initMap() {
    const ownerCenter = [27.7172, 85.3240];

    map = L.map('land-map').setView(ownerCenter, 16);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
    }).addTo(map);

    const bounds = [
        [ownerCenter[0] - 0.003, ownerCenter[1] - 0.004],
        [ownerCenter[0] + 0.003, ownerCenter[1] + 0.004],
    ];

    ownerBoundary = L.rectangle(bounds, {
        color: '#00FF00',
        weight: 2,
        fillColor: '#00FF00',
        fillOpacity: 0.15,
    }).addTo(map);

    L.marker(ownerCenter).addTo(map).bindPopup('Owner Land Center').openPopup();

    loadFieldMarkers();
    loadWeatherData();
    setupHourlyUpdates();

    setTimeout(() => {
        simulateFieldData();
    }, 1000);
}

function loadFieldMarkers() {
    fetch('fetch_fields.php')
        .then(res => res.json())
        .then(fields => {
            // Clear existing markers
            markers.forEach(m => map.removeLayer(m.marker));
            markers = [];

            fields.forEach(field => {
                // Filter by crop if selected
                if (selectedCropFilter !== 'all' && field.crop_type !== selectedCropFilter) {
                    return;
                }

                const cropColor = cropColors[field.crop_type] || '#3498DB';
                const pesticideStatus = field.pesticide_level || 'normal';
                const pestLevel = pesticidesLevel[pesticideStatus];

                // Create custom HTML for popup with pesticide info
                const popupHTML = `
                    <div style="font-size: 12px; width: 200px;">
                        <b>${field.field_name}</b><br>
                        <strong>Crop:</strong> ${field.crop_type}<br>
                        <strong>Area:</strong> ${field.area} hectares<br>
                        <div style="margin-top: 8px; padding: 8px; background: ${cropColor}20; border-left: 4px solid ${cropColor};">
                            <strong>Crop Health:</strong> ${field.crop_health || 'Good'}<br>
                            <strong>Soil Humidity:</strong> ${field.soil_humidity || '68%'}<br>
                            <strong>Temperature:</strong> ${field.temperature || '24°C'}
                        </div>
                        <div style="margin-top: 8px; padding: 8px; background: ${pestLevel.color}20; border-left: 4px solid ${pestLevel.color};">
                            <strong>Pesticide Level:</strong> ${pestLevel.icon} ${pesticideStatus.toUpperCase()}<br>
                            <strong>Last Updated:</strong> ${new Date().toLocaleTimeString()}
                        </div>
                    </div>
                `;

                const marker = L.marker([field.latitude, field.longitude], {
                    icon: createCustomIcon(cropColor, pesticideStatus)
                })
                    .bindPopup(popupHTML)
                    .addTo(map);

                markers.push({ marker, field });
            });
        })
        .catch(error => console.error('Error loading fields:', error));
}

function createCustomIcon(cropColor, pesticideStatus) {
    const pestLevel = pesticidesLevel[pesticideStatus];
    return L.divIcon({
        className: 'custom-icon',
        html: `<div style="
            background: ${cropColor};
            border: 3px solid ${pestLevel.color};
            border-radius: 50%;
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 16px;
            color: white;
            box-shadow: 0 2px 4px rgba(0,0,0,0.3);
        ">${pestLevel.icon}</div>`,
        iconSize: [30, 30],
        popupAnchor: [0, -15]
    });
}

function loadWeatherData() {
    fetch('fetch_weather.php')
        .then(res => res.json())
        .then(data => {
            weatherData = data;
            updateWeatherDisplay();
        })
        .catch(error => console.error('Error loading weather:', error));
}

function updateWeatherDisplay() {
    const weatherElement = document.getElementById('weather-info');
    if (!weatherElement) return;

    const current = weatherData.current || {};
    const hourly = weatherData.hourly || [];

    let html = `
        <div style="padding: 10px; background: #f8f9fa; border-radius: 5px;">
            <strong>Current Weather</strong><br>
            Temperature: ${current.temperature || '24'}°C<br>
            Humidity: ${current.humidity || '65'}%<br>
            Condition: ${current.condition || 'Clear'}<br>
            Wind Speed: ${current.wind_speed || '5'} km/h
        </div>
    `;

    if (hourly.length > 0) {
        html += '<strong style="display: block; margin-top: 10px;">Hourly Forecast</strong>';
        html += '<div style="display: flex; gap: 5px; overflow-x: auto; margin-top: 5px;">';
        hourly.slice(0, 6).forEach(h => {
            html += `
                <div style="min-width: 80px; padding: 8px; background: #e8f4f8; border-radius: 4px; text-align: center; font-size: 11px;">
                    <strong>${h.time}</strong><br>
                    ${h.temperature}°C<br>
                    ${h.condition}
                </div>
            `;
        });
        html += '</div>';
    }

    weatherElement.innerHTML = html;
}

function setupHourlyUpdates() {
    // Update field markers every hour
    setInterval(() => {
        loadFieldMarkers();
        loadWeatherData();
    }, 3600000); // 1 hour

    // Quick update every 15 minutes
    setInterval(() => {
        simulateFieldData();
    }, 900000); // 15 minutes
}

function simulateFieldData() {
    const feed = document.getElementById('realtime-feed');
    if (!feed) {
        return;
    }

    const data = [
        'Soil humidity: 68%',
        'Temperature: 24°C',
        'Pesticide index: normal',
        'NDVI crop health: good',
        'Irrigation status: active',
        'Neighbor field detected near boundary',
    ];

    feed.innerHTML = data.map(item => `<p>${item}</p>`).join('');
}

function filterByCrop(cropType) {
    selectedCropFilter = cropType;
    loadFieldMarkers();
}

function toggleMapType(mapType) {
    if (mapType === 'google' && currentMapType !== 'google') {
        switchToGoogleMap();
        currentMapType = 'google';
    } else if (mapType === 'satellite' && currentMapType !== 'satellite') {
        switchToSatelliteMap();
        currentMapType = 'satellite';
    } else if (mapType === 'leaflet' && currentMapType !== 'leaflet') {
        switchToLeafletMap();
        currentMapType = 'leaflet';
    }
}

function switchToGoogleMap() {
    if (!document.getElementById('google-map')) {
        alert('Google Maps integration requires API key setup. Using satellite view instead.');
        switchToSatelliteMap();
        return;
    }

    const mapContainer = document.getElementById('land-map');
    mapContainer.style.display = 'none';
    document.getElementById('google-map').style.display = 'block';

    // Initialize Google Map (requires API key)
    const farmLocation = { lat: 27.7172, lng: 85.3240 };
    const gMap = new google.maps.Map(document.getElementById('google-map'), {
        center: farmLocation,
        zoom: 18,
        mapTypeId: 'satellite',
        tilt: 45,
        heading: 90,
    });

    new google.maps.Marker({
        position: farmLocation,
        map: gMap,
        title: 'My Farm',
    });
}

function switchToSatelliteMap() {
    const mapContainer = document.getElementById('land-map');
    if (document.getElementById('google-map')) {
        document.getElementById('google-map').style.display = 'none';
    }
    mapContainer.style.display = 'block';

    // Switch Leaflet to satellite
    map.eachLayer(layer => {
        if (layer instanceof L.TileLayer) {
            map.removeLayer(layer);
        }
    });

    L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
        attribution: '&copy; Tiles &copy; Esri',
        maxZoom: 20
    }).addTo(map);

    currentMapType = 'satellite';
}

function switchToLeafletMap() {
    const mapContainer = document.getElementById('land-map');
    if (document.getElementById('google-map')) {
        document.getElementById('google-map').style.display = 'none';
    }
    mapContainer.style.display = 'block';

    map.eachLayer(layer => {
        if (layer instanceof L.TileLayer) {
            map.removeLayer(layer);
        }
    });

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
    }).addTo(map);

    currentMapType = 'leaflet';
}

function exportWeatherData() {
    const csv = `Timestamp,Temperature,Humidity,Condition,Wind Speed\n${
        weatherData.hourly?.map(h => `${h.time},${h.temperature},${h.humidity},${h.condition},${h.wind_speed}`).join('\n') || ''
    }`;

    const blob = new Blob([csv], { type: 'text/csv' });
    const url = window.URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = 'weather-data.csv';
    a.click();
    window.URL.revokeObjectURL(url);
}

document.addEventListener('DOMContentLoaded', () => {
    if (document.getElementById('land-map')) {
        initMap();
    }
});
