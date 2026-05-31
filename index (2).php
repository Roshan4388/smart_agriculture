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
        <section class="hero-section scroll-reveal">
            <div class="hero-content">
                <span class="eyebrow">AI Precision Farming</span>
                <h1 class="typed-text" data-typed="Transform farming with precision AI;Unlock smarter irrigation and crop health;Grow sustainably with data-driven insights">Transform farming with precision AI</h1>
                <p>Actionable field intelligence for crop health, irrigation automation, weather prediction, pest detection and marketplace growth.</p>
                <div class="hero-actions">
                    <a class="btn-primary" href="<?= $baseUrl ?>/sign-in.php">Start Monitoring</a>
                    <a class="btn-secondary" href="<?= $baseUrl ?>/modules/maps/land-map.php">Explore Land Map</a>
                </div>
                <div class="hero-stats">
                    <div class="stat-card">
                        <strong>24/7</strong>
                        <span>Real-time sensor updates</span>
                    </div>
                    <div class="stat-card">
                        <strong>8</strong>
                        <span>AI-powered farming tools</span>
                    </div>
                    <div class="stat-card">
                        <strong>95%</strong>
                        <span>Precision irrigation accuracy</span>
                    </div>
                    <div class="stat-card">
                        <strong>12k</strong>
                        <span>Insights delivered monthly</span>
                    </div>
                </div>
            </div>
            <div class="hero-image">
                <div class="illustration-glow"></div>
                <img src="assets/images/backgrounds/agriculture-bg.svg" alt="Smart agriculture illustration">
            </div>
        </section>

        <section class="dashboard-section features-section scroll-reveal">
            <div class="section-header">
                <div>
                    <span class="eyebrow">Smart agriculture features</span>
                    <h2>Precision farming tools for modern growers</h2>
                </div>
                <p>Combine sensor intelligence, AI analytics, irrigation automation and marketplace support in one eco-friendly dashboard.</p>
            </div>
            <div class="feature-grid">
                <article class="feature-card">
                    <span class="feature-icon">🌱</span>
                    <h3>Crop Monitoring</h3>
                    <p>Track growth, stress, and yield potential across your fields in real time.</p>
                </article>
                <article class="feature-card">
                    <span class="feature-icon">💧</span>
                    <h3>Irrigation Control</h3>
                    <p>Automate water delivery with soil moisture-based schedules and smart valves.</p>
                </article>
                <article class="feature-card">
                    <span class="feature-icon">☁️</span>
                    <h3>Weather Forecast</h3>
                    <p>Stay ahead of storms, heat spells and rain with local farm weather analysis.</p>
                </article>
                <article class="feature-card">
                    <span class="feature-icon">🗺️</span>
                    <h3>Land Mapping</h3>
                    <p>Visualize plots, boundaries and irrigation zones with interactive maps.</p>
                </article>
                <article class="feature-card">
                    <span class="feature-icon">🛒</span>
                    <h3>Marketplace</h3>
                    <p>Connect produce to buyers and manage inventory from a smart farm hub.</p>
                </article>
                <article class="feature-card">
                    <span class="feature-icon">🐞</span>
                    <h3>Pest Detection</h3>
                    <p>Detect threats early using AI image scanning and sensor anomaly alerts.</p>
                </article>
                <article class="feature-card">
                    <span class="feature-icon">📈</span>
                    <h3>Analytics</h3>
                    <p>Use crop, soil and weather analytics to make confident planting decisions.</p>
                </article>
            </div>
        </section>

        <section class="live-dashboard scroll-reveal">
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

        <section class="info-section scroll-reveal">
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