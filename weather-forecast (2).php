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
    <title>Weather Forecast - <?= htmlspecialchars($config['app_name']) ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/responsive.css">
</head>
<body>
    <?php include __DIR__ . '/includes/navbar.php'; ?>
    <main class="dashboard-page">
        <section class="dashboard-section">
            <div class="section-header">
                <h1>Weather Forecast</h1>
                <p>Short-term forecast data for irrigation planning, pest control, and field work.</p>
            </div>
            <div class="dashboard-grid feature-grid">
                <article class="dashboard-card feature-card">
                    <h3>Today</h3>
                    <p>Partly cloudy, 24°C. Light winds and a chance of evening rain.</p>
                </article>
                <article class="dashboard-card feature-card">
                    <h3>Tomorrow</h3>
                    <p>Cloudy with possible showers, 23°C. Ideal for soil soaking before planting.</p>
                </article>
                <article class="dashboard-card feature-card">
                    <h3>Next 3 Days</h3>
                    <p>Moderate rain expected, keep irrigation on standby and avoid overwatering.</p>
                </article>
            </div>
        </section>
    </main>
    <footer class="site-footer"><p>&copy; <?= date('Y') ?> <?= htmlspecialchars($config['app_name']) ?>.</p></footer>
    <script src="assets/js/site.js"></script>
</body>
</html>
