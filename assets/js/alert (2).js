// Alert system JS
function initAlerts() {
    const alertBox = document.getElementById('alert-box');
    if (!alertBox) {
        return;
    }

    const alertData = [
        { time: '08:12', message: 'Unauthorized activity near your east boundary.', severity: 'high' },
        { time: '11:30', message: 'Drone detected above north field.', severity: 'medium' },
        { time: '14:45', message: 'Pesticide application recommended for crop block A.', severity: 'info' },
    ];

    alertBox.innerHTML = alertData.map(alert => `
        <div class="alert-item alert-${alert.severity}">
            <strong>${alert.time}</strong> - ${alert.message}
        </div>
    `).join('');
}

window.addEventListener('load', () => {
    initAlerts();

    setInterval(() => {
        const unauthorized = Math.random() > 0.7;
        if (unauthorized) {
            const alertBox = document.getElementById('alert-box');
            if (alertBox) {
                const alertHtml = `
                    <div class="alert-item alert-high">
                        <strong>${new Date().toLocaleTimeString()}</strong> - Potential unauthorized land access detected.
                    </div>
                `;
                alertBox.insertAdjacentHTML('afterbegin', alertHtml);
                window.alert('Alert: Potential unauthorized land access detected.');
            }
        }
    }, 45000);
});
