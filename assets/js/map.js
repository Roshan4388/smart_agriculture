// Map module JS
let map;
let ownerBoundary;
let markers = [];
let currentMapType = 'leaflet';
let selectedCropFilters = new Set();
const cropOptions = ['Rice', 'Wheat', 'Corn', 'Vegetables', 'Fruits'];
let weatherData = {};
let planMarker = null;
let planCircle = null;
let planLocation = { lat: 27.7172, lng: 85.3240 };

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

function getApiBasePath() {
    return window.location.pathname.includes('/modules/maps/') ? '../../api/' : '../api/';
}

function isCropSelected(cropType) {
    return selectedCropFilters.size === 0 || selectedCropFilters.has(cropType);
}

function updateFilterButtons() {
    const filterButtons = document.querySelectorAll('.btn-filter');
    filterButtons.forEach(btn => {
        const cropType = btn.getAttribute('data-crop-type');
        if (!cropType) return;
        if (cropType === 'all') {
            btn.classList.toggle('active', selectedCropFilters.size === 0);
        } else {
            btn.classList.toggle('active', selectedCropFilters.has(cropType));
        }
    });
}

function updateCropCheckboxes() {
    const inputs = document.querySelectorAll('#crop-selectors input[type="checkbox"]');
    inputs.forEach(input => {
        input.checked = selectedCropFilters.size === 0 || selectedCropFilters.has(input.value);
    });
}

function updateSelectedCropLabels() {
    const selectedLabel = document.getElementById('plan-selected-crops');
    if (!selectedLabel) return;
    const crops = selectedCropFilters.size === 0 ? cropOptions : Array.from(selectedCropFilters);
    selectedLabel.textContent = crops.length ? crops.join(', ') : 'All crops selected';
}

function createPlanMarker(center) {
    planLocation = center;
    planMarker = L.marker([center.lat, center.lng], {
        draggable: true,
        title: 'Drag to place cultivation plan',
        icon: L.divIcon({
            className: 'plan-marker',
            html: '<div style="background: #3498DB; border: 2px solid white; border-radius: 50%; width: 28px; height: 28px; display: flex; align-items: center; justify-content: center; color: white; font-size: 14px;">P</div>'
        })
    }).addTo(map);

    planCircle = L.circle([center.lat, center.lng], {
        radius: 70,
        color: '#3498DB',
        fillColor: '#3498DB',
        fillOpacity: 0.14
    }).addTo(map);

    planMarker.on('drag', onPlanMarkerMove);
    planMarker.on('dragend', onPlanMarkerMove);
    updatePlanDetails();
}

function onPlanMarkerMove(event) {
    const latlng = event.target.getLatLng();
    planLocation = { lat: latlng.lat, lng: latlng.lng };
    if (planCircle) {
        planCircle.setLatLng(latlng);
    }
    updatePlanDetails();
}

function updatePlanDetails() {
    const details = document.getElementById('plan-details');
    if (!details) return;
    const selected = selectedCropFilters.size === 0 ? cropOptions : Array.from(selectedCropFilters);
    details.innerHTML = `
        <div><strong>Planned cultivation point:</strong> ${planLocation.lat.toFixed(5)}, ${planLocation.lng.toFixed(5)}</div>
        <div><strong>Selected crops:</strong> ${selected.length ? selected.join(', ') : 'All crops'}</div>
        <div><strong>Mapped radius:</strong> 70 meters</div>
    `;
}

function showPlanStatus(message, type = 'success') {
    const status = document.getElementById('plan-status');
    if (!status) return;
    status.textContent = message;
    status.style.color = type === 'error' ? '#E74C3C' : '#27AE60';
}

function toggleCropSelection(cropType) {
    if (selectedCropFilters.has(cropType)) {
        selectedCropFilters.delete(cropType);
    } else {
        selectedCropFilters.add(cropType);
    }
    updateCropCheckboxes();
    updateFilterButtons();
    updateSelectedCropLabels();
    loadFieldMarkers();
}

function clearSelectedCrops() {
    selectedCropFilters.clear();
    updateCropCheckboxes();
    updateFilterButtons();
    updateSelectedCropLabels();
    loadFieldMarkers();
}

function savePlan() {
    const signatureName = document.getElementById('plan-signature')?.value.trim() || '';
    const notes = document.getElementById('plan-notes')?.value.trim() || '';
    const selectedCrops = selectedCropFilters.size === 0 ? cropOptions : Array.from(selectedCropFilters);

    if (!signatureName) {
        showPlanStatus('Please enter your name or signature before saving.', 'error');
        return;
    }
    if (!selectedCrops.length) {
        showPlanStatus('Please select at least one crop to save the plan.', 'error');
        return;
    }

    const payload = {
        signature_name: signatureName,
        notes: notes,
        selected_crops: selectedCrops,
        plan_lat: planLocation.lat,
        plan_lng: planLocation.lng,
        plan_area_meters: 70
    };

    fetch(`${getApiBasePath()}save_plan.php`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(payload)
    })
        .then(res => res.json())
        .then(result => {
            if (result.success) {
                showPlanStatus('Plan saved successfully with your signature.');
            } else {
                showPlanStatus(result.error || 'Unable to save the plan. Please try again.', 'error');
            }
        })
        .catch(() => {
            showPlanStatus('Unable to reach the server. Check your connection and try again.', 'error');
        });
}

function filterByCrop(cropType) {
    if (cropType === 'all') {
        selectedCropFilters.clear();
    } else {
        selectedCropFilters.clear();
        selectedCropFilters.add(cropType);
    }
    updateFilterButtons();
    updateCropCheckboxes();
    updateSelectedCropLabels();
    loadFieldMarkers();
}

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
    createPlanMarker({ lat: ownerCenter[0], lng: ownerCenter[1] });
    updateFilterButtons();
    updateCropCheckboxes();
    updateSelectedCropLabels();

    loadFieldMarkers();
    loadWeatherData();
    setupHourlyUpdates();

    setTimeout(() => {
        simulateFieldData();
    }, 1000);
}

function loadFieldMarkers() {
    fetch(`${getApiBasePath()}fetch_fields.php`)
        .then(res => res.json())
        .then(fields => {
            // Clear existing markers
            markers.forEach(m => map.removeLayer(m.marker));
            markers = [];

            fields.forEach(field => {
                    if (!isCropSelected(field.crop_type)) {
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
    fetch(`${getApiBasePath()}fetch_weather.php`)
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
