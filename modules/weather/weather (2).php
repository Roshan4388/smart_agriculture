<?php
require_once __DIR__ . '/../../includes/session.php';
require_once __DIR__ . '/../../includes/auth.php';
requireLogin();
$config = require __DIR__ . '/../../includes/config.php';

$currentWeather = [
    'temperature' => 25,
    'humidity' => 64,
    'wind' => '8 km/h',
    'forecast' => 'Partly cloudy with scattered rain later in the week.',
    'suitability' => 'Good for rice, maize, and leafy vegetables.',
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Weather - <?= htmlspecialchars($config['app_name']) ?></title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="stylesheet" href="../../assets/css/responsive.css">
</head>
<body>
    <?php include __DIR__ . '/../../includes/navbar.php'; ?>
    <main class="dashboard-page">
        <section class="dashboard-section">
            <h1>Current Weather</h1>
            <div class="dashboard-grid">
                <article class="dashboard-card">
                    <h3>Temperature</h3>
                    <p><?= htmlspecialchars($currentWeather['temperature']) ?>°C</p>
                </article>
                <article class="dashboard-card">
                    <h3>Humidity</h3>
                    <p><?= htmlspecialchars($currentWeather['humidity']) ?>%</p>
                </article>
                <article class="dashboard-card">
                    <h3>Wind</h3>
                    <p><?= htmlspecialchars($currentWeather['wind']) ?></p>
                </article>
                <article class="dashboard-card">
                    <h3>Crop Suitability</h3>
                    <p><?= htmlspecialchars($currentWeather['suitability']) ?></p>
                </article>
            </div>
            <div class="recommendation-card">
                <h2>Weather Insight</h2>
                <p><?= htmlspecialchars($currentWeather['forecast']) ?></p>
            </div>
        </section>
    </main>
</body>
</html>
