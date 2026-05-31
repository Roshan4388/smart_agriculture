<?php
require_once __DIR__ . '/../../includes/session.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/crop-data.php';
requireLogin();
$config = require __DIR__ . '/../../includes/config.php';

$cropId = intval($_GET['id'] ?? 0);
$crop = null;
if ($cropId > 0) {
    $stmt = $mysqli->prepare('SELECT id, name, season, soil_type, expected_yield, description, tips FROM crops WHERE id = ? LIMIT 1');
    if ($stmt) {
        $stmt->bind_param('i', $cropId);
        $stmt->execute();
        $result = $stmt->get_result();
        $crop = $result->fetch_assoc();
    }
    if (!$crop) {
        $crop = findStaticCropProfileById($cropId);
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crop Details - <?= htmlspecialchars($config['app_name']) ?></title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="stylesheet" href="../../assets/css/responsive.css">
</head>

<body>
    <?php include __DIR__ . '/../../includes/navbar.php'; ?>
    <main class="dashboard-page">
        <section class="dashboard-section">
            <?php if (!$crop) : ?>
                <h1>Crop not found</h1>
                <p>The crop profile you requested could not be found. Return to the crop list to view available profiles.</p>
                <a class="btn-secondary" href="crop-list.php">Back to crop list</a>
            <?php else : ?>
                <h1><?= htmlspecialchars($crop['name']) ?></h1>
                <p><strong>Season:</strong> <?= htmlspecialchars($crop['season']) ?></p>
                <p><strong>Soil type:</strong> <?= htmlspecialchars($crop['soil_type']) ?></p>
                <p><strong>Expected yield:</strong> <?= htmlspecialchars($crop['expected_yield']) ?></p>
                <h2>About this crop</h2>
                <p><?= nl2br(htmlspecialchars($crop['description'])) ?></p>
                <h2>Best practices</h2>
                <p><?= nl2br(htmlspecialchars($crop['tips'])) ?></p>
                <a class="btn-secondary" href="crop-list.php">Back to crop list</a>
            <?php endif; ?>
        </section>
    </main>
</body>

</html>