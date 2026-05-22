<?php
require_once __DIR__ . '/../../includes/session.php';
require_once __DIR__ . '/../../includes/auth.php';
requireLogin();
$config = require __DIR__ . '/../../includes/config.php';

$forecast = [
    ['day' => 'Monday', 'temp' => '24°C', 'condition' => 'Clouds'],
    ['day' => 'Tuesday', 'temp' => '26°C', 'condition' => 'Rain'],
    ['day' => 'Wednesday', 'temp' => '23°C', 'condition' => 'Showers'],
    ['day' => 'Thursday', 'temp' => '25°C', 'condition' => 'Clouds'],
    ['day' => 'Friday', 'temp' => '27°C', 'condition' => 'Sunny'],
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Weather Forecast - <?= htmlspecialchars($config['app_name']) ?></title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="stylesheet" href="../../assets/css/responsive.css">
</head>
<body>
    <?php include __DIR__ . '/../../includes/navbar.php'; ?>
    <main class="dashboard-page">
        <section class="dashboard-section">
            <h1>7-day Weather Forecast</h1>
            <p>Plan your irrigation, pest control, and harvesting based on upcoming weather patterns.</p>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Day</th>
                        <th>Temperature</th>
                        <th>Condition</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($forecast as $day) : ?>
                        <tr>
                            <td><?= htmlspecialchars($day['day']) ?></td>
                            <td><?= htmlspecialchars($day['temp']) ?></td>
                            <td><?= htmlspecialchars($day['condition']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>
    </main>
</body>
</html>
