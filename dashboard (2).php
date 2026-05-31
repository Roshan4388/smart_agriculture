<?php
require_once __DIR__ . '/includes/session.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/db.php';
requireLogin();
$config = require __DIR__ . '/includes/config.php';
$user = getCurrentUser($mysqli);

function fetchCount($mysqli, $query)
{
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
    'recommendation' => 'Rice and maize are suitable. Monitor soil moisture and check pests every 3 days.'
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
    <link rel="stylesheet" href="assets/css/dashboard.css">
    <link rel="stylesheet" href="assets/css/responsive.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
</head>

<body>
    <?php include __DIR__ . '/includes/navbar.php'; ?>
    <main class="dashboard-page">
        <section class="dashboard-section hero-panel scroll-reveal">
            <div class="hero-panel-inner">
                <div class="hero-copy">
                    <div class="hero-copy-header">
                        <div>
                            <span class="eyebrow">Precision AI</span>
                            <h1>Transform farming with precision AI</h1>
                            <p>Actionable field intelligence for crop health, weather prediction,
                                pest detection, and marketplace growth.</p>
                        </div>
                        <button class="theme-toggle">🌙 Dark mode</button>
                    </div>

                    <div class="hero-actions">
                        <a href="sensor-data.php" class="btn-primary">Start Monitoring</a>
                        <a href="modules/maps/land-map.php" class="btn-secondary">Explore Land Map</a>
                    </div>

                    <div class="hero-stats">
                        <article class="stat-card">
                            <span class="stat-icon">⟳</span>
                            <strong>24/7</strong>
                            <small>Real-time sensor updates</small>
                        </article>
                        <article class="stat-card">
                            <span class="stat-icon">⚙️</span>
                            <strong>8</strong>
                            <small>AI-powered farming tools</small>
                        </article>
                        <article class="stat-card">
                            <span class="stat-icon">💧</span>
                            <strong>95%</strong>
                            <small>Precision irrigation accuracy</small>
                        </article>
                        <article class="stat-card">
                            <span class="stat-icon">📈</span>
                            <strong>12k</strong>
                            <small>Insights delivered monthly</small>
                        </article>
                    </div>
                </div>

                <div class="hero-visual">
                    <div class="visual-card">
                        <div class="visual-overlay">
                            <span>Live farm map</span>
                            <strong>4 active zones</strong>
                        </div>
                        <div class="visual-pattern pattern-1"></div>
                        <div class="visual-pattern pattern-2"></div>
                        <div class="visual-pattern pattern-3"></div>
                    </div>
                </div>
            </div>
        </section>

        <section class="dashboard-section quicklinks-panel scroll-reveal">
            <div class="section-header">
                <div>
                    <span class="eyebrow">Quick Access</span>
                    <h2>Tap a section to see its details</h2>
                </div>
                <p>Choose a farming workflow area and get the right tools, data, or marketplace page instantly.</p>
            </div>
            <div class="section-card-grid">
                <button type="button" class="section-card active" data-section="expert-consult">Expert Consult</button>
                <button type="button" class="section-card" data-section="farmer-groups">Farmer Group</button>
                <button type="button" class="section-card" data-section="view-marketplace">View Marketplace</button>
                <button type="button" class="section-card" data-section="expert-groups">Expert Groups</button>
                <button type="button" class="section-card" data-section="market-status">Market Status</button>
                <button type="button" class="section-card" data-section="market-assistant">Market Assistant</button>
            </div>
            <div class="section-details" id="section-details">
                <h2 id="section-detail-title">Expert Consult</h2>
                <p id="section-detail-copy">Submit a consultation request and connect with agriculture specialists for
                    crop planning, pest management, and market advice.</p>
                <a id="section-detail-link" class="btn-secondary" href="modules/experts/consultation.php">Open Expert
                    Consult</a>
            </div>
        </section>

        <section class="dashboard-grid sensor-grid scroll-reveal">
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

        <section class="dashboard-grid chart-grid scroll-reveal">
            <article class="dashboard-card chart-card">
                <div class="section-header">
                    <h2>Soil Moisture Trend</h2>
                    <span>Live sensor values</span>
                </div>
                <div class="chart-lines">
                    <div class="chart-line" style="width: 78%;">78%</div>
                    <div class="chart-line" style="width: 64%;">64%</div>
                    <div class="chart-line" style="width: 42%;">42%</div>
                    <div class="chart-line" style="width: 55%;">55%</div>
                </div>
            </article>
            <article class="dashboard-card chart-card">
                <div class="section-header">
                    <h2>Temperature & Humidity</h2>
                    <span>Plant comfort range</span>
                </div>
                <div class="chart-bars">
                    <div><strong>Temp</strong><span style="width: 68%;"></span></div>
                    <div><strong>Humidity</strong><span style="width: 82%;"></span></div>
                    <div><strong>Soil</strong><span style="width: 48%;"></span></div>
                </div>
            </article>
            <article class="dashboard-card weather-widget">
                <div class="section-header">
                    <h2>Weather Widget</h2>
                    <span><?= htmlspecialchars($currentWeather['condition']) ?></span>
                </div>
                <div class="weather-grid">
                    <div>
                        <strong><?= htmlspecialchars($currentWeather['temperature']) ?>°C</strong>
                        <small>Temperature</small>
                    </div>
                    <div>
                        <strong><?= htmlspecialchars($currentWeather['humidity']) ?>%</strong>
                        <small>Humidity</small>
                    </div>
                    <div>
                        <strong><?= htmlspecialchars($currentWeather['rainfall']) ?>%</strong>
                        <small>Rain chance</small>
                    </div>
                </div>
                <p><?= htmlspecialchars($currentWeather['recommendation']) ?></p>
            </article>
        </section>

        <section class="dashboard-section status-panel scroll-reveal">
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

        <section class="dashboard-grid farmer-grid scroll-reveal">
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

        <section class="dashboard-section map-panel scroll-reveal">
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
    <script>
    const sectionInfo = {
        'expert-consult': {
            title: 'Expert Consult',
            copy: 'Submit a consultation request and connect with agriculture specialists for crop planning, pest control, and market guidance.',
            href: 'modules/experts/consultation.php'
        },
        'farmer-groups': {
            title: 'Farmer Group',
            copy: 'Join or manage farmer groups to collaborate on land planning, shared crop sales, and field insights.',
            href: 'modules/groups/groups.php'
        },
        'view-marketplace': {
            title: 'View Marketplace',
            copy: 'Browse marketplace listings, add products, and manage your buyer connections in one place.',
            href: 'modules/marketplace/products.php'
        },
        'expert-groups': {
            title: 'Expert Groups',
            copy: 'Explore groups led by agriculture experts and access specialized support for farm decisions.',
            href: 'modules/groups/expert-groups.php'
        },
        'market-status': {
            title: 'Market Status',
            copy: 'Review current market demand, price signals, and assistant recommendations for your produce.',
            href: 'market-status.php'
        },
        'market-assistant': {
            title: 'Market Assistant',
            copy: 'Use marketplace assistant tools to select buyer roles, compare prices, and prepare products for sale.',
            href: 'market-assistant.php'
        }
    };

    const cards = document.querySelectorAll('.section-card');
    const detailTitle = document.getElementById('section-detail-title');
    const detailCopy = document.getElementById('section-detail-copy');
    const detailLink = document.getElementById('section-detail-link');

    function showSection(key) {
        const info = sectionInfo[key];
        if (!info) return;

        cards.forEach(card => {
            const isActive = card.dataset.section === key;
            card.classList.toggle('active', isActive);
            card.setAttribute('aria-pressed', isActive ? 'true' : 'false');
        });

        detailTitle.textContent = info.title;
        detailCopy.textContent = info.copy;
        detailLink.href = info.href;
        detailLink.textContent = `Go to ${info.title}`;
    }

    cards.forEach(card => {
        // Mouse click
        card.addEventListener('click', () => showSection(card.dataset.section));

        // Keyboard accessibility
        card.setAttribute('role', 'button');
        card.setAttribute('tabindex', '0');
        card.setAttribute('aria-pressed', card.classList.contains('active') ? 'true' : 'false');
        card.addEventListener('keydown', (e) => {
            const key = e.key;
            if (key === 'Enter' || key === ' ') {
                e.preventDefault();
                showSection(card.dataset.section);
            }
        });
    });
    </script>
</body>

</html>
