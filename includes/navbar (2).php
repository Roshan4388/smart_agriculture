<?php
$config = require __DIR__ . '/config.php';
$baseUrl = $config['base_url'];
?>
<nav class="main-nav">
    <ul>
        <li><a href="<?= $baseUrl ?>/index.php"><span class="nav-icon" aria-hidden="true"></span><span data-i18n="nav.home">Home</span></a></li>
        <li><a href="<?= $baseUrl ?>/dashboard.php"><span class="nav-icon" aria-hidden="true"></span><span data-i18n="nav.dashboard">Dashboard</span></a></li>
        <li><a href="<?= $baseUrl ?>/sensor-data.php"><span class="nav-icon" aria-hidden="true"></span><span data-i18n="nav.sensorData">Sensor Data</span></a></li>
        <li><a href="<?= $baseUrl ?>/crop-report.php"><span class="nav-icon" aria-hidden="true"></span><span data-i18n="nav.cropReport">Crop Report</span></a></li>
        <li><a href="<?= $baseUrl ?>/alerts.php"><span class="nav-icon" aria-hidden="true"></span><span data-i18n="nav.alerts">Alerts</span></a></li>
        <li><a href="<?= $baseUrl ?>/modules/maps/land-map.php"><span class="nav-icon" aria-hidden="true"></span><span data-i18n="nav.landMap">Land Map</span></a></li>
        <li><a href="<?= $baseUrl ?>/modules/crops/recommendation.php"><span class="nav-icon" aria-hidden="true"></span><span data-i18n="nav.cropMonitoring">Crop Monitoring</span></a></li>
        <li><a href="<?= $baseUrl ?>/modules/crops/crop-list.php"><span class="nav-icon" aria-hidden="true"></span><span data-i18n="nav.cropLibrary">Crop Library</span></a></li>
        <li><a href="<?= $baseUrl ?>/diseases.php"><span class="nav-icon" aria-hidden="true"></span><span data-i18n="nav.diseaseGuide">Disease Guide</span></a></li>
        <li><a href="<?= $baseUrl ?>/modules/experts/consultation.php"><span class="nav-icon" aria-hidden="true"></span><span data-i18n="nav.expertConsult">Expert Consult</span></a></li>
        <li><a href="<?= $baseUrl ?>/modules/groups/groups.php"><span class="nav-icon" aria-hidden="true"></span><span data-i18n="nav.farmerGroups">Farmer Groups</span></a></li>
        <li><a href="<?= $baseUrl ?>/modules/marketplace/products.php"><span class="nav-icon" aria-hidden="true"></span><span data-i18n="nav.marketplace">View Marketplace</span></a></li>
        <li><a href="<?= $baseUrl ?>/modules/groups/expert-groups.php"><span class="nav-icon" aria-hidden="true"></span><span data-i18n="nav.expertGroups">Expert Groups</span></a></li>
        <li><a href="<?= $baseUrl ?>/market-status.php"><span class="nav-icon" aria-hidden="true"></span><span data-i18n="nav.marketStatus">Market Status</span></a></li>
        <li><a href="<?= $baseUrl ?>/weather-forecast.php"><span class="nav-icon" aria-hidden="true"></span><span data-i18n="nav.weather">Weather</span></a></li>
        <li><a href="<?= $baseUrl ?>/reports.php"><span class="nav-icon" aria-hidden="true"></span><span data-i18n="nav.reports">Reports</span></a></li>
        <li><a href="<?= $baseUrl ?>/settings.php"><span class="nav-icon" aria-hidden="true"></span><span data-i18n="nav.settings">Settings</span></a></li>
        <?php if (isset($_SESSION['user_id'])) : ?>
            <li class="nav-user">Hello, <?= htmlspecialchars($_SESSION['user_name']) ?></li>
            <li><a href="<?= $baseUrl ?>/logout.php"><span class="nav-icon" aria-hidden="true"></span><span data-i18n="nav.logout">Logout</span></a></li>
        <?php else : ?>
            <li><a href="<?= $baseUrl ?>/sign-in.php"><span class="nav-icon" aria-hidden="true"></span><span data-i18n="nav.login">Login</span></a></li>
            <li><a href="<?= $baseUrl ?>/register.php"><span class="nav-icon" aria-hidden="true"></span><span data-i18n="nav.register">Register</span></a></li>
        <?php endif; ?>
        <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin') : ?>
            <li><a href="<?= $baseUrl ?>/admin/admin-dashboard.php"><span class="nav-icon" aria-hidden="true"></span><span data-i18n="nav.admin">Admin</span></a></li>
        <?php endif; ?>
    </ul>
    <button class="theme-toggle" type="button" title="Toggle dark mode" data-i18n="theme.darkMode">Dark mode</button>
</nav>
<script src="<?= $baseUrl ?>/assets/js/site.js" defer></script>
