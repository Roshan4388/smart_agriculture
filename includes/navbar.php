<?php
$config = require __DIR__ . '/config.php';
$baseUrl = $config['base_url'];
?>
<nav class="main-nav">
    <ul>
        <li><a href="<?= $baseUrl ?>/index.php"><span class="nav-icon">Home</span>Home</a></li>
        <li><a href="<?= $baseUrl ?>/dashboard.php"><span class="nav-icon">Dash</span>Dashboard</a></li>
        <li><a href="<?= $baseUrl ?>/sensor-data.php"><span class="nav-icon">Data</span>Sensor Data</a></li>
        <li><a href="<?= $baseUrl ?>/crop-report.php"><span class="nav-icon">Crop</span>Crop Report</a></li>
        <li><a href="<?= $baseUrl ?>/alerts.php"><span class="nav-icon">Alert</span>Alerts</a></li>
        <li><a href="<?= $baseUrl ?>/modules/maps/land-map.php"><span class="nav-icon">Map</span>Land Map</a></li>
        <li><a href="<?= $baseUrl ?>/modules/crops/recommendation.php"><span class="nav-icon">Grow</span>Crop Monitoring</a></li>
        <li><a href="<?= $baseUrl ?>/modules/crops/crop-list.php"><span class="nav-icon">Book</span>Crop Library</a></li>
        <li><a href="<?= $baseUrl ?>/diseases.php"><span class="nav-icon">Pest</span>Disease Guide</a></li>
        <li><a href="<?= $baseUrl ?>/modules/experts/consultation.php"><span class="nav-icon">Help</span>Expert Consult</a></li>
        <li><a href="<?= $baseUrl ?>/modules/groups/groups.php"><span class="nav-icon">Team</span>Farmer Groups</a></li>
        <li><a href="<?= $baseUrl ?>/modules/marketplace/products.php"><span class="nav-icon">Shop</span>View Marketplace</a></li>
        <li><a href="<?= $baseUrl ?>/modules/groups/expert-groups.php"><span class="nav-icon">Pro</span>Expert Groups</a></li>
        <li><a href="<?= $baseUrl ?>/market-status.php"><span class="nav-icon">Price</span>Market Status</a></li>
        <li><a href="<?= $baseUrl ?>/weather-forecast.php"><span class="nav-icon">Sky</span>Weather</a></li>
        <li><a href="<?= $baseUrl ?>/reports.php"><span class="nav-icon">Chart</span>Reports</a></li>
        <li><a href="<?= $baseUrl ?>/settings.php"><span class="nav-icon">Set</span>Settings</a></li>
        <?php if (isset($_SESSION['user_id'])) : ?>
            <li class="nav-user">Hello, <?= htmlspecialchars($_SESSION['user_name']) ?></li>
            <li><a href="<?= $baseUrl ?>/logout.php"><span class="nav-icon">Exit</span>Logout</a></li>
        <?php else : ?>
            <li><a href="<?= $baseUrl ?>/sign-in.php"><span class="nav-icon">Lock</span>Login</a></li>
            <li><a href="<?= $baseUrl ?>/register.php"><span class="nav-icon">Join</span>Register</a></li>
        <?php endif; ?>
        <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin') : ?>
            <li><a href="<?= $baseUrl ?>/admin/admin-dashboard.php"><span class="nav-icon">Admin</span>Admin</a></li>
        <?php endif; ?>
    </ul>
    <button class="theme-toggle" type="button" title="Toggle dark mode">Dark mode</button>
</nav>
<script src="<?= $baseUrl ?>/assets/js/site.js" defer></script>
