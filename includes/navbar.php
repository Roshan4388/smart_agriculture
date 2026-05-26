<?php
// Navbar include
$config = require __DIR__ . '/config.php';
$baseUrl = $config['base_url'];
?>
<nav class="main-nav">
    <ul>
        <li><a href="<?= $baseUrl ?>/index.php">Home</a></li>
        <li><a href="<?= $baseUrl ?>/sensor-data.php">Sensor Data</a></li>
        <li><a href="<?= $baseUrl ?>/irrigation-control.php">Irrigation Control</a></li>
        <li><a href="<?= $baseUrl ?>/crop-report.php">Crop Report</a></li>
        <li><a href="<?= $baseUrl ?>/alerts.php">Alerts</a></li>
        <li><a href="<?= $baseUrl ?>/settings.php">Settings</a></li>
        <li><a href="<?= $baseUrl ?>/dashboard.php">Dashboard</a></li>
        <li><a href="<?= $baseUrl ?>/modules/maps/land-map.php">Land Map</a></li>
        <li><a href="<?= $baseUrl ?>/modules/crops/recommendation.php">Crop Monitoring</a></li>
        <li><a href="<?= $baseUrl ?>/weather-forecast.php">Weather Forecast</a></li>
        <li><a href="<?= $baseUrl ?>/reports.php">Reports</a></li>
        <?php if (isset($_SESSION['user_id'])) : ?>
            <li class="nav-user">Hello, <?= htmlspecialchars($_SESSION['user_name']) ?></li>
            <li><a href="<?= $baseUrl ?>/logout.php">Logout</a></li>
        <?php else : ?>
            <li><a href="<?= $baseUrl ?>/sign-in.php">Login</a></li>
            <li><a href="<?= $baseUrl ?>/register.php">Register</a></li>
        <?php endif; ?>
        <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin') : ?>
            <li><a href="<?= $baseUrl ?>/admin/admin-dashboard.php">Admin</a></li>
        <?php endif; ?>
    </ul>
    <button class="theme-toggle" type="button" title="Toggle dark mode">🌙</button>
</nav>
<script src="<?= $baseUrl ?>/assets/js/site.js" defer></script>
