<?php
require_once __DIR__ . '/includes/session.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/db.php';
requireLogin();
$config = require __DIR__ . '/includes/config.php';
$user = getCurrentUser($mysqli);

function fetchCount($mysqli, $query) {
    $result = $mysqli->query($query);
    if (!$result) {
        return 0;
    }
    $row = $result->fetch_row();
    return intval($row[0]);
}

$cropCount = fetchCount($mysqli, 'SELECT COUNT(*) FROM crops');
$productCount = fetchCount($mysqli, 'SELECT COUNT(*) FROM products');
$orderCount = fetchCount($mysqli, 'SELECT COUNT(*) FROM orders');

$currentWeather = [
    'temperature' => 24,
    'humidity' => 68,
    'rainfall' => 85,
    'condition' => 'Partly Cloudy',
    'recommendation' => 'Rice and maize are suitable. Maintain irrigation and check pests every 3 days.'
];

$recommendation = 'Rice is currently the strongest recommendation for this season because rainfall and soil moisture are high. Use nitrogen-rich fertilizer in the next planting window.';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - <?= htmlspecialchars($config['app_name']) ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/responsive.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
</head>
<body>
    <?php include __DIR__ . '/includes/navbar.php'; ?>
    <main class="dashboard-page">
        <section class="dashboard-section page-top-panel">
            <div class="page-header">
                <div>
                    <span class="eyebrow">Farmer Dashboard</span>
                    <h1>Monitor your farm in one place</h1>
                    <p>Temperature, humidity, soil moisture, water level, crop health and weather status are all updated in real time for smarter decisions.</p>
                </div>
                <button class="theme-toggle">🌙 Dark mode</button>
            </div>
        </section>

        <section class="dashboard-grid sensor-grid">
            <article class="dashboard-card sensor-card green-card">
                <span class="sensor-title">Temperature</span>
                <strong class="sensor-value">24°C</strong>
                <span class="sensor-note">Stable, safe for morning irrigation</span>
            </article>
            <article class="dashboard-card sensor-card blue-card">
                <span class="sensor-title">Humidity</span>
                <strong class="sensor-value">68%</strong>
                <span class="sensor-note">Normal humidity for most crops</span>
            </article>
            <article class="dashboard-card sensor-card brown-card">
                <span class="sensor-title">Soil Moisture</span>
                <strong class="sensor-value">42%</strong>
                <span class="sensor-note">Needs slight irrigation soon</span>
            </article>
            <article class="dashboard-card sensor-card water-card">
                <span class="sensor-title">Water Level</span>
                <strong class="sensor-value">58%</strong>
                <span class="sensor-note">Tank level adequate for 2 cycles</span>
            </article>
            <article class="dashboard-card sensor-card health-card">
                <span class="sensor-title">Crop Health</span>
                <strong class="sensor-value">Good</strong>
                <span class="sensor-note">Leaf sensors and NDVI are in a healthy range</span>
            </article>
            <article class="dashboard-card sensor-card weather-card">
                <span class="sensor-title">Weather Status</span>
                <strong class="sensor-value">Partly Cloudy</strong>
                <span class="sensor-note">Rain forecast in 4 hours</span>
            </article>
        </section>

        <section class="dashboard-section status-panel">
            <div class="section-header">
                <h2>Alert Center</h2>
            </div>
            <div class="alert-grid">
                <div class="alert-item alert-medium">Low soil moisture ⚠️ — Check drip lines in east field.</div>
                <div class="alert-item alert-high">High temperature 🔥 — Open shade nets and increase misting.</div>
                <div class="alert-item alert-medium">Water tank low 💧 — Refill before next irrigation cycle.</div>
                <div class="alert-item alert-info">Rain forecast ☁️ — Dry set irrigation to passive mode.</div>
            </div>
        </section>

        <section class="dashboard-grid farmer-grid">
            <article class="dashboard-card control-panel">
                <h3>Farmer Control Panel</h3>
                <div class="control-grid">
                    <div class="control-card">
                        <h4>Irrigation</h4>
                        <button class="btn-primary">Turn irrigation ON</button>
                        <button class="btn-secondary">Turn irrigation OFF</button>
                    </div>
                    <div class="control-card">
                        <h4>Sensor Values</h4>
                        <ul>
                            <li>Temperature: 24°C</li>
                            <li>Humidity: 68%</li>
                            <li>Soil moisture: 42%</li>
                            <li>Water level: 58%</li>
                        </ul>
                    </div>
                    <div class="control-card">
                        <h4>Crop Growth</h4>
                        <p>Growth trend is healthy. Next harvest window is in 12 days.</p>
                    </div>
                    <div class="control-card">
                        <h4>Fertilizer Reminder</h4>
                        <p>Apply organic fertilizer in 3 days to support leafy growth.</p>
                    </div>
                </div>
            </article>
            <article class="dashboard-card feature-panel">
                <h3>Project Pages</h3>
                <div class="feature-grid">
                    <a class="feature-card" href="dashboard.php">Dashboard</a>
                    <a class="feature-card" href="modules/crops/recommendation.php">Crop Monitoring</a>
                    <a class="feature-card" href="irrigation-control.php">Irrigation Control</a>
                    <a class="feature-card" href="weather-forecast.php">Weather Forecast</a>
                    <a class="feature-card" href="reports.php">Reports</a>
                    <a class="feature-card" href="settings.php">Settings</a>
                </div>
                <h4>UX Highlights</h4>
                <ul class="recommendation-list">
                    <li>Easy-to-read graphs and status cards</li>
                    <li>Large buttons built for a farmer-friendly interface</li>
                    <li>Green / water / soil theme with clean white surfaces</li>
                    <li>Responsive mobile layout with dark/light mode</li>
                    <li>Real-time status updates and push alerts</li>
                </ul>
            </article>
        </section>

        <section class="dashboard-section map-panel">
            <div class="section-header">
                <h2>3D Farm Visualization</h2>
                <a class="secondary-link" href="modules/maps/land-map.php">View Interactive Map</a>
            </div>
            <div id="land-map" class="map-container"></div>
            <p class="caption">Use the map to inspect your land sections, crop blocks, and virtual irrigation zones.</p>
        </section>
    </main>
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script src="assets/js/map.js"></script>
    <script src="assets/js/alert.js"></script>
</body>
</html>
