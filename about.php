<?php
session_start();
$config = require __DIR__ . '/includes/config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About - <?= htmlspecialchars($config['app_name']) ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/responsive.css">
</head>
<body>
    <?php include __DIR__ . '/includes/navbar.php'; ?>
    <main class="dashboard-page">
        <section class="dashboard-section">
            <h1>About Smart Agriculture</h1>
            <p>Smart Agriculture combines modern data-driven farming tools with traditional field experience.</p>
            <ul>
                <li><strong>Land tracking & crop planning:</strong> Use weather, soil moisture and local season data to choose the best crop.</li>
                <li><strong>3D farm visualization:</strong> View land borders, field shape and terrain with interactive mapping.</li>
                <li><strong>Security monitoring:</strong> Get alerts for unauthorized access or boundary breaches.</li>
                <li><strong>Marketplace:</strong> Sell produce directly, manage buyers, and access live price signals.</li>
                <li><strong>Expert consultation:</strong> Request guidance from agriculture specialists and advisors.</li>
            </ul>
        </section>
    </main>
    <footer class="site-footer">
        <p>&copy; <?= date('Y') ?> <?= htmlspecialchars($config['app_name']) ?>. Built for smarter farming.</p>
    </footer>
</body>
</html>
