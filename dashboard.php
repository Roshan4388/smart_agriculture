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
</head>
<body>
    <?php include __DIR__ . '/includes/navbar.php'; ?>
    <main class="dashboard-page">
        <section class="dashboard-section welcome-panel">
            <h1>Welcome back, <?= htmlspecialchars($user['name']) ?></h1>
            <p>Access land tracking, crop recommendations, weather forecasting, security alerts and market updates from one dashboard.</p>
        </section>

        <section class="dashboard-grid stats-grid">
            <article class="dashboard-card">
                <h3>Total Crop Profiles</h3>
                <p><?= $cropCount ?></p>
            </article>
            <article class="dashboard-card">
                <h3>Marketplace Listings</h3>
                <p><?= $productCount ?></p>
            </article>
            <article class="dashboard-card">
                <h3>Orders Received</h3>
                <p><?= $orderCount ?></p>
            </article>
        </section>

        <section class="dashboard-section map-panel">
            <div class="section-header">
                <h2>3D Land Visualization</h2>
                <a class="secondary-link" href="modules/maps/land-map.php">Open full map</a>
            </div>
            <div id="land-map" class="map-container"></div>
            <p class="caption">Interactive boundary tracking and virtual farm preview. Replace <code>YOUR_GOOGLE_MAPS_API_KEY</code> with your API key.</p>
        </section>

        <section class="dashboard-grid">
            <article class="dashboard-card">
                <h3>Current Weather</h3>
                <ul>
                    <li>Temperature: <?= $currentWeather['temperature'] ?>°C</li>
                    <li>Humidity: <?= $currentWeather['humidity'] ?>%</li>
                    <li>Rainfall chance: <?= $currentWeather['rainfall'] ?>%</li>
                    <li>Condition: <?= htmlspecialchars($currentWeather['condition']) ?></li>
                </ul>
                <p class="recommendation-card"><?= htmlspecialchars($currentWeather['recommendation']) ?></p>
            </article>
            <article class="dashboard-card">
                <h3>Seasonal Crop Recommendation</h3>
                <p><?= htmlspecialchars($recommendation) ?></p>
                <a class="btn-secondary" href="modules/crops/recommendation.php">View recommendations</a>
            </article>
            <article class="dashboard-card">
                <h3>Security Monitoring</h3>
                <p>Unauthorized access detection, boundary crossing alerts, and live device tracking.</p>
                <a class="btn-secondary" href="modules/security/alerts.php">View alerts</a>
            </article>
        </section>

        <section class="dashboard-section">
            <div class="section-header">
                <h2>Quick Actions</h2>
            </div>
            <div class="dashboard-grid action-grid">
                <article class="dashboard-card">
                    <a href="modules/marketplace/products.php">Manage Marketplace</a>
                </article>
                <article class="dashboard-card">
                    <a href="modules/experts/consultation.php">Request Expert Consultation</a>
                </article>
                <article class="dashboard-card">
                    <a href="modules/weather/forecast.php">View Weather Forecast</a>
                </article>
            </div>
        </section>
    </main>
    <script src="assets/js/map.js"></script>
    <script src="assets/js/alert.js"></script>
    <script async defer src="https://maps.googleapis.com/maps/api/js?key=YOUR_GOOGLE_MAPS_API_KEY&callback=initMap"></script>
</body>
</html>
