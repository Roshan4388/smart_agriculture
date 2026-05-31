<?php
require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';
requireAdmin();
$config = require __DIR__ . '/../includes/config.php';

$crops = [];
$result = $mysqli->query('SELECT id, name, season, soil_type, expected_yield FROM crops ORDER BY name ASC');
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $crops[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Crops - <?= htmlspecialchars($config['app_name']) ?></title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/responsive.css">
</head>
<body>
    <?php include __DIR__ . '/../includes/navbar.php'; ?>
    <main class="dashboard-page">
        <section class="dashboard-section">
            <h1>Manage Crop Profiles</h1>
            <p>Review the crop catalog used for recommendation and suitability analysis.</p>
            <table class="data-table">
                <thead>
                    <tr><th>Name</th><th>Season</th><th>Soil Type</th><th>Yield</th></tr>
                </thead>
                <tbody>
                    <?php foreach ($crops as $crop) : ?>
                        <tr>
                            <td><?= htmlspecialchars($crop['name']) ?></td>
                            <td><?= htmlspecialchars($crop['season']) ?></td>
                            <td><?= htmlspecialchars($crop['soil_type']) ?></td>
                            <td><?= htmlspecialchars($crop['expected_yield']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>
    </main>
</body>
</html>
