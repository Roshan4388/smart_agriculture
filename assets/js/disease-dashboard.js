// Live AI disease detection dashboard simulation
const diseaseUpdates = [
    'Field scan complete: no active disease detected.',
    'Leaf image analysis suggests early blight risk in 1 section.',
    'Humidity sensors indicate humid conditions favorable for leaf rust.',
    'AI alert: patchy discoloration detected in greenhouses.',
    'Recommendation: inspect irrigation lines and treat affected leaves.',
    'Field update: plant vigor is stable, continue standard care.',
];

function updateDiseaseDashboard() {
    const feed = document.getElementById('disease-live-feed');
    const status = document.getElementById('disease-status');
    const risk = document.getElementById('disease-risk');
    const type = document.getElementById('disease-type');

    if (!feed || !status || !risk || !type) {
        return;
    }

    const nextIndex = Math.floor(Math.random() * diseaseUpdates.length);
    const nextRisk = Math.max(1, Math.min(98, Math.round(Math.random() * 18 + 4)));
    const conditions = ['Healthy', 'Minor stress', 'Early rust', 'Blight risk', 'Pest alert', 'Water stress'];
    const nextCondition = conditions[Math.floor(Math.random() * conditions.length)];

    status.textContent = nextCondition === 'Healthy' ? 'Stable' : 'Attention';
    risk.textContent = `${nextRisk}%`;
    type.textContent = nextCondition;
    feed.innerHTML = `
        <p>${new Date().toLocaleTimeString()} — ${diseaseUpdates[nextIndex]}</p>
        <p>Detection model running on latest field imagery and sensor data.</p>
    `;
}

document.addEventListener('DOMContentLoaded', () => {
    if (document.querySelector('.live-dashboard')) {
        updateDiseaseDashboard();
        setInterval(updateDiseaseDashboard, 4200);
    }
});
