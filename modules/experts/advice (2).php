<?php
require_once __DIR__ . '/../../includes/session.php';
require_once __DIR__ . '/../../includes/auth.php';
requireLogin();
$config = require __DIR__ . '/../../includes/config.php';

$adviceItems = [
    ['title' => 'Seed selection', 'summary' => 'Select high-yield, climate-resilient seeds suitable for current monsoon conditions.'],
    ['title' => 'Soil preparation', 'summary' => 'Add organic compost and perform soil pH testing before planting.'],
    ['title' => 'Pest control', 'summary' => 'Use integrated pest management and monitor crop health weekly.'],
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Advice - <?= htmlspecialchars($config['app_name']) ?></title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="stylesheet" href="../../assets/css/responsive.css">
</head>
<body>
    <?php include __DIR__ . '/../../includes/navbar.php'; ?>
    <main class="dashboard-page">
        <section class="dashboard-section">
            <h1>Expert Advice</h1>
            <p>Explore practical farming recommendations based on weather, crop health, and market conditions.</p>
            <div class="recommendation-list">
                <?php foreach ($adviceItems as $item) : ?>
                    <div class="recommendation-card">
                        <h3><?= htmlspecialchars($item['title']) ?></h3>
                        <p><?= htmlspecialchars($item['summary']) ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
    </main>
</body>
</html>
