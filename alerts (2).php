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
    <title>Alerts - <?= htmlspecialchars($config['app_name']) ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/responsive.css">
</head>
<body>
    <?php include __DIR__ . '/includes/navbar.php'; ?>
    <main class="dashboard-page">
        <section class="dashboard-section">
            <div class="section-header">
                <h1>Alerts</h1>
                <p>Active notifications and warnings from farm sensors and weather forecasts.</p>
            </div>
            <div class="alert-grid">
                <div class="alert-item alert-high">High temperature detected 🔥 — Start shade system immediately.</div>
                <div class="alert-item alert-medium">Low soil moisture ⚠️ — Irrigate field block B in the next hour.</div>
                <div class="alert-item alert-medium">Water tank low 💧 — Refill required before tonight.</div>
                <div class="alert-item alert-info">Rain forecast ☁️ — Reduce sprinkler operation until after the storm.</div>
            </div>
        </section>
    </main>
    <footer class="site-footer"><p>&copy; <?= date('Y') ?> <?= htmlspecialchars($config['app_name']) ?>.</p></footer>
    <script src="assets/js/site.js"></script>
</body>
</html>
