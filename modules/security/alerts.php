<?php
require_once __DIR__ . '/../../includes/session.php';
require_once __DIR__ . '/../../includes/auth.php';
requireLogin();
$config = require __DIR__ . '/../../includes/config.php';

$alerts = [
    ['time' => '02:18', 'message' => 'Unauthorized entry detected at north boundary.', 'severity' => 'high'],
    ['time' => '08:22', 'message' => 'Device login from field sensor B successful.', 'severity' => 'info'],
    ['time' => '14:50', 'message' => 'Drone scan completed. No intrusions detected.', 'severity' => 'medium'],
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Security Alerts - <?= htmlspecialchars($config['app_name']) ?></title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="stylesheet" href="../../assets/css/responsive.css">
</head>
<body>
    <?php include __DIR__ . '/../../includes/navbar.php'; ?>
    <main class="dashboard-page">
        <section class="dashboard-section">
            <h1>Security Alerts</h1>
            <p>Track boundary warnings, sensor activity, and live alerts from your field network.</p>
            <div class="alert-box">
                <?php foreach ($alerts as $alert) : ?>
                    <div class="alert-item alert-<?= htmlspecialchars($alert['severity']) ?>">
                        <strong><?= htmlspecialchars($alert['time']) ?></strong> - <?= htmlspecialchars($alert['message']) ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
    </main>
</body>
</html>
