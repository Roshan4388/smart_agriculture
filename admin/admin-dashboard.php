<?php
require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';
requireAdmin();
$config = require __DIR__ . '/../includes/config.php';

function fetchCount($mysqli, $sql) {
    $result = $mysqli->query($sql);
    if (!$result) return 0;
    $row = $result->fetch_row();
    return intval($row[0]);
}

$userCount = fetchCount($mysqli, 'SELECT COUNT(*) FROM users');
$cropCount = fetchCount($mysqli, 'SELECT COUNT(*) FROM crops');
$productCount = fetchCount($mysqli, 'SELECT COUNT(*) FROM products');
$orderCount = fetchCount($mysqli, 'SELECT COUNT(*) FROM orders');
$consultationCount = fetchCount($mysqli, 'SELECT COUNT(*) FROM consultations');
$contactCount = fetchCount($mysqli, 'SELECT COUNT(*) FROM contacts');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - <?= htmlspecialchars($config['app_name']) ?></title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/responsive.css">
</head>
<body>
    <?php include __DIR__ . '/../includes/navbar.php'; ?>
    <main class="dashboard-page">
        <section class="dashboard-section">
            <h1>Admin Dashboard</h1>
            <p>Manage users, crops, marketplace listings, orders, and reports from a single admin panel.</p>
        </section>
        <section class="dashboard-grid stats-grid">
            <article class="dashboard-card"><h3>Users</h3><p><?= $userCount ?></p></article>
            <article class="dashboard-card"><h3>Crops</h3><p><?= $cropCount ?></p></article>
            <article class="dashboard-card"><h3>Products</h3><p><?= $productCount ?></p></article>
            <article class="dashboard-card"><h3>Orders</h3><p><?= $orderCount ?></p></article>
            <article class="dashboard-card"><h3>Consultations</h3><p><?= $consultationCount ?></p></article>
            <article class="dashboard-card"><h3>Contacts</h3><p><?= $contactCount ?></p></article>
        </section>
        <section class="dashboard-section">
            <h2>Admin Actions</h2>
            <div class="recommendation-list">
                <div class="recommendation-card"><a href="manage-users.php">Manage users</a></div>
                <div class="recommendation-card"><a href="manage-crops.php">Manage crop profiles</a></div>
                <div class="recommendation-card"><a href="manage-market.php">Manage marketplace</a></div>
                <div class="recommendation-card"><a href="reports.php">View reports</a></div>
            </div>
        </section>
    </main>
</body>
</html>
