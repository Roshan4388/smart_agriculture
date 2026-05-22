<?php
session_start();
$config = require __DIR__ . '/includes/config.php';
$baseUrl = $config['base_url'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($config['app_name']) ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/responsive.css">
</head>
<body>
    <?php include __DIR__ . '/includes/navbar.php'; ?>
    <main class="dashboard-page home-page">
        <section class="hero-section">
            <div class="hero-content">
                <span class="eyebrow">Smart Agriculture System</span>
                <h1>Transforming farming with data, maps, and AI-driven guidance.</h1>
                <p>Plan crops, monitor land, secure your field, connect with buyers, and get expert advice from one dashboard.</p>
                <div class="hero-actions">
                    <a class="btn-primary" href="<?= $baseUrl ?>/sign-in.php">Get Started</a>
                    <a class="btn-secondary" href="<?= $baseUrl ?>/modules/maps/land-map.php">View Land Map</a>
                </div>
            </div>
            <div class="hero-image">
                <img src="assets/images/backgrounds/agriculture-bg.svg" alt="Smart agriculture illustration">
            </div>
        </section>

        <section class="feature-grid">
            <article class="feature-card">
                <h2>Land Tracking & Crop Suitability</h2>
                <p>Analyze soil moisture, rainfall, weather, and seasonal patterns to recommend the best crop for your field.</p>
            </article>
            <article class="feature-card">
                <h2>3D Visualization</h2>
                <p>Visualize farm boundaries, terrain, and satellite maps in a modern, interactive interface.</p>
            </article>
            <article class="feature-card">
                <h2>Security Alerts</h2>
                <p>Receive intrusion, boundary breach, and device login alerts to protect your farm area.</p>
            </article>
            <article class="feature-card">
                <h2>Marketplace & Experts</h2>
                <p>Sell produce, manage orders, and consult agriculture experts for smart farming decisions.</p>
            </article>
        </section>

        <section class="info-section">
            <h2>Why this system matters</h2>
            <p>Modern farmers need accurate field data and actionable recommendations. This platform turns traditional agriculture into a data-driven ecosystem.</p>
            <ul>
                <li>Reduce crop failure with weather-aware recommendations.</li>
                <li>Boost profit with marketplace connections and expert advice.</li>
                <li>Protect land using boundary alerts and monitoring.</li>
            </ul>
        </section>
    </main>
    <footer class="site-footer">
        <p>&copy; <?= date('Y') ?> <?= htmlspecialchars($config['app_name']) ?>. All rights reserved.</p>
    </footer>
</body>
</html>
