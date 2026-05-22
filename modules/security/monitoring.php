<?php
require_once __DIR__ . '/../../includes/session.php';
require_once __DIR__ . '/../../includes/auth.php';
requireLogin();
$config = require __DIR__ . '/../../includes/config.php';

$monitoring = [
    ['metric' => 'Boundary status', 'value' => 'Secure'],
    ['metric' => 'Unauthorized events', 'value' => '1 in last 24h'],
    ['metric' => 'Sensor connectivity', 'value' => '96%'],
    ['metric' => 'Drone coverage', 'value' => 'North field complete'],
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monitoring - <?= htmlspecialchars($config['app_name']) ?></title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="stylesheet" href="../../assets/css/responsive.css">
</head>
<body>
    <?php include __DIR__ . '/../../includes/navbar.php'; ?>
    <main class="dashboard-page">
        <section class="dashboard-section">
            <h1>Security Monitoring</h1>
            <p>Review the current state of field security sensors and boundary protection.</p>
            <div class="dashboard-grid">
                <?php foreach ($monitoring as $item) : ?>
                    <article class="dashboard-card">
                        <h3><?= htmlspecialchars($item['metric']) ?></h3>
                        <p><?= htmlspecialchars($item['value']) ?></p>
                    </article>
                <?php endforeach; ?>
            </div>
            <p class="caption">Use alerts and tracking pages to investigate suspicious activity in your land area.</p>
        </section>
    </main>
</body>
</html>
