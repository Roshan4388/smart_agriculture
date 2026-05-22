<?php
require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';
requireAdmin();
$config = require __DIR__ . '/../includes/config.php';

$totalRevenue = 0;
$result = $mysqli->query('SELECT SUM(total_price) FROM orders');
if ($result) {
    $row = $result->fetch_row();
    $totalRevenue = floatval($row[0]);
}
$topCategory = 'Vegetables';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Reports - <?= htmlspecialchars($config['app_name']) ?></title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/responsive.css">
</head>
<body>
    <?php include __DIR__ . '/../includes/navbar.php'; ?>
    <main class="dashboard-page">
        <section class="dashboard-section">
            <h1>Reports</h1>
            <div class="dashboard-grid stats-grid">
                <article class="dashboard-card"><h3>Total Revenue</h3><p>Rs. <?= number_format($totalRevenue, 2) ?></p></article>
                <article class="dashboard-card"><h3>Top Category</h3><p><?= htmlspecialchars($topCategory) ?></p></article>
            </div>
            <p>Use this report dashboard to monitor marketplace performance and plan improvements.</p>
        </section>
    </main>
</body>
</html>
