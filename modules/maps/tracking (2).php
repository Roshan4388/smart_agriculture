<?php
require_once __DIR__ . '/../../includes/session.php';
require_once __DIR__ . '/../../includes/auth.php';
requireLogin();
$config = require __DIR__ . '/../../includes/config.php';

$trackingPoints = [
    ['time' => '08:10', 'device' => 'Boundary Sensor A', 'status' => 'Online', 'note' => 'No movement detected.'],
    ['time' => '11:40', 'device' => 'Drone Camera', 'status' => 'Online', 'note' => 'Survey completed for north field.'],
    ['time' => '13:50', 'device' => 'GPS Tracker', 'status' => 'Online', 'note' => 'All boundary tags within safe range.'],
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tracking - <?= htmlspecialchars($config['app_name']) ?></title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="stylesheet" href="../../assets/css/responsive.css">
</head>
<body>
    <?php include __DIR__ . '/../../includes/navbar.php'; ?>
    <main class="dashboard-page">
        <section class="dashboard-section">
            <h1>Field Tracking and Device Status</h1>
            <p>Monitor farm sensors, GPS trackers, and drone activity across the land boundary.</p>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Time</th>
                        <th>Device</th>
                        <th>Status</th>
                        <th>Note</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($trackingPoints as $point) : ?>
                        <tr>
                            <td><?= htmlspecialchars($point['time']) ?></td>
                            <td><?= htmlspecialchars($point['device']) ?></td>
                            <td><?= htmlspecialchars($point['status']) ?></td>
                            <td><?= htmlspecialchars($point['note']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>
    </main>
</body>
</html>
