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

        <section class="live-dashboard">
            <div class="dashboard-panel panel-3d">
                <div class="dashboard-panel-header">
                    <span>AI Disease Detection</span>
                    <h2>Live Crop Health</h2>
                </div>
                <p>Continuously monitor crop health using AI-powered detection indicators and receive alerts for disease risk in real time.</p>
                <div class="detection-overview">
                    <div class="detection-stat">
                        <strong id="disease-status">Stable</strong>
                        <small>Current status</small>
                    </div>
                    <div class="detection-stat">
                        <strong id="disease-risk">5%</strong>
                        <small>Risk score</small>
                    </div>
                    <div class="detection-stat">
                        <strong id="disease-type">No issues found</strong>
                        <small>Detected condition</small>
                    </div>
                </div>
                <div class="detection-feed" id="disease-live-feed">
                    <p>Analyzing leaf images and field sensors...</p>
                </div>
            </div>
            <div class="info-panel panel-3d">
                <h2>Live Detection Notes</h2>
                <p>AI scans incoming field data and suggests actions like targeted inspection, irrigation checks, or immediate treatment when risk rises.</p>
                <ul>
                    <li>Dynamic disease alerts at farm scale.</li>
                    <li>Vegetation stress detection with image-based analysis.</li>
                    <li>Action suggestions for early intervention.</li>
                </ul>
            </div>
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
    <script src="assets/js/disease-dashboard.js"></script>
</body>
</html>
