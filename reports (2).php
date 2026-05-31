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
    <title>Reports - <?= htmlspecialchars($config['app_name']) ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/responsive.css">
</head>
<body>
    <?php include __DIR__ . '/includes/navbar.php'; ?>
    <main class="dashboard-page">
        <section class="dashboard-section">
            <div class="section-header">
                <h1>Reports</h1>
                <p>Summary reports for farm performance, production estimates, and sensor trends.</p>
            </div>
            <div class="dashboard-grid feature-grid">
                <article class="dashboard-card feature-card">
                    <h3>Weekly Report</h3>
                    <p>Yield forecast, irrigation usage, and sensor anomalies are highlighted.</p>
                </article>
                <article class="dashboard-card feature-card">
                    <h3>Season Report</h3>
                    <p>Performance across crop cycles and recommended planting windows.</p>
                </article>
                <article class="dashboard-card feature-card">
                    <h3>Alerts Log</h3>
                    <p>Historical alerts for temperature, moisture, and water levels.</p>
                </article>
            </div>
        </section>
    </main>
    <footer class="site-footer"><p>&copy; <?= date('Y') ?> <?= htmlspecialchars($config['app_name']) ?>.</p></footer>
    <script src="assets/js/site.js"></script>
</body>
</html>
