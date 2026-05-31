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
    <title>Irrigation Control - <?= htmlspecialchars($config['app_name']) ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/responsive.css">
</head>
<body>
    <?php include __DIR__ . '/includes/navbar.php'; ?>
    <main class="dashboard-page">
        <section class="dashboard-section">
            <div class="section-header">
                <h1>Irrigation Control</h1>
                <p>Turn irrigation systems on or off, track pump status, and review automatic schedule settings.</p>
            </div>
            <div class="dashboard-grid farmer-grid">
                <article class="dashboard-card control-card">
                    <h3>Current Irrigation</h3>
                    <p>Status: <strong>OFF</strong></p>
                    <button class="btn-primary">Start Irrigation</button>
                    <button class="btn-secondary">Stop Irrigation</button>
                </article>
                <article class="dashboard-card control-card">
                    <h3>Water Tank</h3>
                    <p>Level: <strong>58%</strong></p>
                    <p>Estimated runtime: 2 cycles</p>
                </article>
                <article class="dashboard-card control-card">
                    <h3>Next Scheduled Run</h3>
                    <p>Tomorrow at 06:00 AM</p>
                    <p>Duration: 45 minutes</p>
                </article>
            </div>
        </section>
    </main>
    <footer class="site-footer"><p>&copy; <?= date('Y') ?> <?= htmlspecialchars($config['app_name']) ?>.</p></footer>
    <script src="assets/js/site.js"></script>
</body>
</html>
