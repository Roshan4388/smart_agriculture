<?php
require_once __DIR__ . '/includes/session.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/db.php';
requireLogin();
$config = require __DIR__ . '/includes/config.php';
$user = getCurrentUser($mysqli);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sensor Data - <?= htmlspecialchars($config['app_name']) ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/responsive.css">
</head>
<body>
    <?php include __DIR__ . '/includes/navbar.php'; ?>
    <main class="dashboard-page">
        <section class="dashboard-section">
            <div class="section-header">
                <h1>Sensor Data</h1>
                <p>Live readings from temperature, humidity, soil moisture, water level, and crop health sensors.</p>
            </div>
            <div class="dashboard-grid sensor-grid">
                <article class="dashboard-card sensor-card green-card">
                    <span class="sensor-title">Temperature</span>
                    <strong class="sensor-value">24°C</strong>
                    <span class="sensor-note">Latest reading updated seconds ago.</span>
                </article>
                <article class="dashboard-card sensor-card blue-card">
                    <span class="sensor-title">Humidity</span>
                    <strong class="sensor-value">68%</strong>
                    <span class="sensor-note">Ideal for crops in the current season.</span>
                </article>
                <article class="dashboard-card sensor-card brown-card">
                    <span class="sensor-title">Soil Moisture</span>
                    <strong class="sensor-value">42%</strong>
                    <span class="sensor-note">Slightly below optimum for leafy vegetables.</span>
                </article>
                <article class="dashboard-card sensor-card water-card">
                    <span class="sensor-title">Water Level</span>
                    <strong class="sensor-value">58%</strong>
                    <span class="sensor-note">Tank reserve sufficient for now.</span>
                </article>
                <article class="dashboard-card sensor-card health-card">
                    <span class="sensor-title">Crop Health</span>
                    <strong class="sensor-value">Good</strong>
                    <span class="sensor-note">NDVI and leaf analysis are stable.</span>
                </article>
            </div>
        </section>
    </main>
    <footer class="site-footer"><p>&copy; <?= date('Y') ?> <?= htmlspecialchars($config['app_name']) ?>.</p></footer>
    <script src="assets/js/site.js"></script>
</body>
</html>
