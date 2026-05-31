<?php
require_once __DIR__ . '/../../includes/session.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/db.php';
requireLogin();
$config = require __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/crop-data.php';

$crops = [];
$result = $mysqli->query('SELECT id, name, season, soil_type, expected_yield FROM crops ORDER BY name ASC');
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $crops[] = $row;
    }
}
if (empty($crops)) {
    $crops = getStaticCropProfiles();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crop List - <?= htmlspecialchars($config['app_name']) ?></title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="stylesheet" href="../../assets/css/responsive.css">
</head>

<body>
    <?php include __DIR__ . '/../../includes/navbar.php'; ?>
    <main class="dashboard-page">
        <section class="dashboard-section">
            <h1>Crop Library</h1>
            <p>Explore a library of 30 crop profiles, choose the right crop for your weather and soil, and open full details for each variety.</p>
            <div class="form-group">
                <label for="crop-select">Select a crop to view details</label>
                <select id="crop-select" onchange="if(this.value) window.location.href = this.value;">
                    <option value="">Choose a crop</option>
                    <?php foreach ($crops as $crop) : ?>
                        <option value="crop-details.php?id=<?= intval($crop['id']) ?>"><?= htmlspecialchars($crop['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <?php if (empty($crops)) : ?>
                <div class="alert-item alert-info">No crops are available yet. Use the admin panel to add crop profiles.</div>
            <?php else : ?>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Season</th>
                            <th>Soil Type</th>
                            <th>Expected Yield</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($crops as $crop) : ?>
                            <tr>
                                <td><?= htmlspecialchars($crop['name']) ?></td>
                                <td><?= htmlspecialchars($crop['season']) ?></td>
                                <td><?= htmlspecialchars($crop['soil_type']) ?></td>
                                <td><?= htmlspecialchars($crop['expected_yield']) ?></td>
                                <td><a href="crop-details.php?id=<?= intval($crop['id']) ?>">View details</a></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </section>
    </main>
</body>

</html>