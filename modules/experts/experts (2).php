<?php
require_once __DIR__ . '/../../includes/session.php';
require_once __DIR__ . '/../../includes/auth.php';
requireLogin();
$config = require __DIR__ . '/../../includes/config.php';

$experts = [
    ['name' => 'Dr. Sita Thapa', 'specialty' => 'Crop science', 'location' => 'Kathmandu'],
    ['name' => 'Rajesh Koirala', 'specialty' => 'Soil nutrition', 'location' => 'Chitwan'],
    ['name' => 'Mina Gurung', 'specialty' => 'Pest management', 'location' => 'Pokhara'],
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Experts - <?= htmlspecialchars($config['app_name']) ?></title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="stylesheet" href="../../assets/css/responsive.css">
</head>
<body>
    <?php include __DIR__ . '/../../includes/navbar.php'; ?>
    <main class="dashboard-page">
        <section class="dashboard-section">
            <h1>Agriculture Experts</h1>
            <p>Find expert advice for crop planning, soil nutrition, pest control, and market access.</p>
            <div class="recommendation-list">
                <?php foreach ($experts as $expert) : ?>
                    <div class="recommendation-card">
                        <h3><?= htmlspecialchars($expert['name']) ?></h3>
                        <p><strong>Specialty:</strong> <?= htmlspecialchars($expert['specialty']) ?></p>
                        <p><strong>Region:</strong> <?= htmlspecialchars($expert['location']) ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
            <a class="btn-secondary" href="consultation.php">Request consultation</a>
        </section>
    </main>
</body>
</html>
