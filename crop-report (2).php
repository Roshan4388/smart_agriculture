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
    <title>Crop Report - <?= htmlspecialchars($config['app_name']) ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/responsive.css">
</head>
<body>
    <?php include __DIR__ . '/includes/navbar.php'; ?>
    <main class="dashboard-page">
        <section class="dashboard-section">
            <div class="section-header">
                <h1>Crop Report</h1>
                <p>Review crop growth, health scores, nutrient reminders, and harvest readiness.</p>
            </div>
            <div class="dashboard-grid sensor-grid">
                <article class="dashboard-card feature-card">
                    <h3>Plant Health Summary</h3>
                    <p>Overall crop health is strong, with a 92% growth score this week.</p>
                </article>
                <article class="dashboard-card feature-card">
                    <h3>Growth Stage</h3>
                    <p>Vegetables: Vegetative phase<br>Grains: Tillering stage</p>
                </article>
                <article class="dashboard-card feature-card">
                    <h3>Yield Forecast</h3>
                    <p>Expected production is within target range for the current field season.</p>
                </article>
            </div>
        </section>
    </main>
    <footer class="site-footer"><p>&copy; <?= date('Y') ?> <?= htmlspecialchars($config['app_name']) ?>.</p></footer>
    <script src="assets/js/site.js"></script>
</body>
</html>
