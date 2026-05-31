<?php
require_once __DIR__ . '/../../includes/session.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/db.php';
requireLogin();
$config = require __DIR__ . '/../../includes/config.php';

$season = 'Monsoon';
$soilMoisture = 72;
$rainfall = 82;
$recommendations = [
    'Rice' => 'High yield and strong suitability for wet land this season.',
    'Maize' => 'Good secondary crop after rice harvest with moderate irrigation.',
    'Vegetables' => 'Suitable in plots with well-drained soil and drip irrigation.',
];
$alerts = [
    'Crop health is stable, continue regular soil checks.',
    'Pesticide application is recommended in 5 days for rice block A.',
    'Rain forecast suggests irrigation may be delayed by 3 days.',
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recommendations - <?= htmlspecialchars($config['app_name']) ?></title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="stylesheet" href="../../assets/css/responsive.css">
</head>
<body>
    <?php include __DIR__ . '/../../includes/navbar.php'; ?>
    <main class="dashboard-page">
        <section class="dashboard-section">
            <h1>Crop & Soil Suitability Recommendations</h1>
            <p>The system analyzes weather, soil moisture, and seasonal data to suggest the best crops for your land.</p>
            <div class="recommendation-list">
                <div class="recommendation-card">
                    <h2>Season</h2>
                    <p><?= htmlspecialchars($season) ?></p>
                </div>
                <div class="recommendation-card">
                    <h2>Soil Moisture</h2>
                    <p><?= htmlspecialchars($soilMoisture) ?>% — ideal for water-loving crops.</p>
                </div>
                <div class="recommendation-card">
                    <h2>Rainfall Forecast</h2>
                    <p><?= htmlspecialchars($rainfall) ?>% chance of rain over the next 7 days.</p>
                </div>
            </div>

            <h2>Top Crop Matches</h2>
            <div class="recommendation-list">
                <?php foreach ($recommendations as $crop => $text) : ?>
                    <div class="recommendation-card">
                        <h3><?= htmlspecialchars($crop) ?></h3>
                        <p><?= htmlspecialchars($text) ?></p>
                    </div>
                <?php endforeach; ?>
            </div>

            <h2>Action Alerts</h2>
            <div class="alert-box">
                <?php foreach ($alerts as $alert) : ?>
                    <div class="alert-item alert-info"><?= htmlspecialchars($alert) ?></div>
                <?php endforeach; ?>
            </div>
        </section>
    </main>
</body>
</html>
