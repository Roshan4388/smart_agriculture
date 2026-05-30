<?php
$config = require __DIR__ . '/config.php';
$baseUrl = $config['base_url'];
?>
<nav class="main-nav">
    <ul>
        <li><a href="<?= $baseUrl ?>/index.php"><span class="nav-icon" aria-hidden="true"></span>Home</a></li>
        <li><a href="<?= $baseUrl ?>/dashboard.php"><span class="nav-icon" aria-hidden="true"></span>Dashboard</a></li>
        <li><a href="<?= $baseUrl ?>/sensor-data.php"><span class="nav-icon" aria-hidden="true"></span>Sensor Data</a></li>
        <li><a href="<?= $baseUrl ?>/crop-report.php"><span class="nav-icon" aria-hidden="true"></span>Crop Report</a></li>
        <li><a href="<?= $baseUrl ?>/alerts.php"><span class="nav-icon" aria-hidden="true"></span>Alerts</a></li>
        <li><a href="<?= $baseUrl ?>/modules/maps/land-map.php"><span class="nav-icon" aria-hidden="true"></span>Land Map</a></li>
        <li><a href="<?= $baseUrl ?>/modules/crops/recommendation.php"><span class="nav-icon" aria-hidden="true"></span>Crop Monitoring</a></li>
        <li><a href="<?= $baseUrl ?>/modules/crops/crop-list.php"><span class="nav-icon" aria-hidden="true"></span>Crop Library</a></li>
        <li><a href="<?= $baseUrl ?>/diseases.php"><span class="nav-icon" aria-hidden="true"></span>Disease Guide</a></li>
        <li><a href="<?= $baseUrl ?>/modules/experts/consultation.php"><span class="nav-icon" aria-hidden="true"></span>Expert Consult</a></li>
        <li><a href="<?= $baseUrl ?>/modules/groups/groups.php"><span class="nav-icon" aria-hidden="true"></span>Farmer Groups</a></li>
        <li><a href="<?= $baseUrl ?>/modules/marketplace/products.php"><span class="nav-icon" aria-hidden="true"></span>View Marketplace</a></li>
        <li><a href="<?= $baseUrl ?>/modules/groups/expert-groups.php"><span class="nav-icon" aria-hidden="true"></span>Expert Groups</a></li>
        <li><a href="<?= $baseUrl ?>/market-status.php"><span class="nav-icon" aria-hidden="true"></span>Market Status</a></li>
        <li><a href="<?= $baseUrl ?>/weather-forecast.php"><span class="nav-icon" aria-hidden="true"></span>Weather</a></li>
        <li><a href="<?= $baseUrl ?>/reports.php"><span class="nav-icon" aria-hidden="true"></span>Reports</a></li>
        <li><a href="<?= $baseUrl ?>/settings.php"><span class="nav-icon" aria-hidden="true"></span>Settings</a></li>
        <?php if (isset($_SESSION['user_id'])) : ?>
            <li class="nav-user">Hello, <?= htmlspecialchars($_SESSION['user_name']) ?></li>
            <li><a href="<?= $baseUrl ?>/logout.php"><span class="nav-icon" aria-hidden="true"></span>Logout</a></li>
        <?php else : ?>
            <li><a href="<?= $baseUrl ?>/sign-in.php"><span class="nav-icon" aria-hidden="true"></span>Login</a></li>
            <li><a href="<?= $baseUrl ?>/register.php"><span class="nav-icon" aria-hidden="true"></span>Register</a></li>
        <?php endif; ?>
        <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin') : ?>
            <li><a href="<?= $baseUrl ?>/admin/admin-dashboard.php"><span class="nav-icon" aria-hidden="true"></span>Admin</a></li>
        <?php endif; ?>
    </ul>
    <button class="theme-toggle" type="button" title="Toggle dark mode">Dark mode</button>
</nav>
<script src="<?= $baseUrl ?>/assets/js/site.js" defer></script>
