<?php
// Navbar include
$config = require __DIR__ . '/config.php';
$baseUrl = $config['base_url'];
?>
<nav class="main-nav">
    <ul>
        <li><a href="<?= $baseUrl ?>/index.php"><span class="nav-icon">🏠</span>Home</a></li>
        <li><a href="<?= $baseUrl ?>/sensor-data.php"><span class="nav-icon">🌿</span>Sensor Data</a></li>
        <li><a href="<?= $baseUrl ?>/irrigation-control.php"><span class="nav-icon">💧</span>Irrigation</a></li>
        <li><a href="<?= $baseUrl ?>/crop-report.php"><span class="nav-icon">🌾</span>Crop Report</a></li>
        <li><a href="<?= $baseUrl ?>/alerts.php"><span class="nav-icon">🚨</span>Alerts</a></li>
        <li><a href="<?= $baseUrl ?>/settings.php"><span class="nav-icon">⚙️</span>Settings</a></li>
        <li><a href="<?= $baseUrl ?>/dashboard.php"><span class="nav-icon">📊</span>Dashboard</a></li>
        <li><a href="<?= $baseUrl ?>/modules/maps/land-map.php"><span class="nav-icon">🗺️</span>Land Map</a></li>
        <li><a href="<?= $baseUrl ?>/modules/crops/recommendation.php"><span class="nav-icon">🌱</span>Crop Monitoring</a></li>
        <li><a href="<?= $baseUrl ?>/weather-forecast.php"><span class="nav-icon">☀️</span>Weather</a></li>
        <li><a href="<?= $baseUrl ?>/reports.php"><span class="nav-icon">📈</span>Reports</a></li>
        <?php if (isset($_SESSION['user_id'])) : ?>
            <li class="nav-user">Hello, <?= htmlspecialchars($_SESSION['user_name']) ?></li>
            <li><a href="<?= $baseUrl ?>/logout.php"><span class="nav-icon">🔓</span>Logout</a></li>
        <?php else : ?>
            <li><a href="<?= $baseUrl ?>/sign-in.php"><span class="nav-icon">🔐</span>Login</a></li>
            <li><a href="<?= $baseUrl ?>/register.php"><span class="nav-icon">📝</span>Register</a></li>
        <?php endif; ?>
        <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin') : ?>
            <li><a href="<?= $baseUrl ?>/admin/admin-dashboard.php"><span class="nav-icon">🛠️</span>Admin</a></li>
        <?php endif; ?>
    </ul>
    <button class="theme-toggle" type="button" title="Toggle dark mode">🌙</button>
</nav>
<script src="<?= $baseUrl ?>/assets/js/site.js" defer></script>