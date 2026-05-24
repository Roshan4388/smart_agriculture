// Map module JS
let map;
let ownerBoundary;

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

    setTimeout(() => {
        simulateFieldData();
    }, 1000);
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

document.addEventListener('DOMContentLoaded', () => {
    if (document.getElementById('land-map')) {
        initMap();
    }
});
