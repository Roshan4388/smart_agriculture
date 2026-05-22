// Map module JS
let map;
let ownerBoundary;

function initMap() {
    const ownerCenter = { lat: 37.7749, lng: -122.4194 };

    map = new google.maps.Map(document.getElementById('land-map'), {
        zoom: 16,
        center: ownerCenter,
        mapTypeId: 'satellite',
        tilt: 45,
    });

    ownerBoundary = new google.maps.Rectangle({
        strokeColor: '#00FF00',
        strokeOpacity: 0.8,
        strokeWeight: 2,
        fillColor: '#00FF00',
        fillOpacity: 0.15,
        map: map,
        bounds: {
            north: ownerCenter.lat + 0.003,
            south: ownerCenter.lat - 0.003,
            east: ownerCenter.lng + 0.004,
            west: ownerCenter.lng - 0.004,
        },
    });

    new google.maps.Marker({
        position: ownerCenter,
        map: map,
        title: 'Owner Land Center',
    });

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
