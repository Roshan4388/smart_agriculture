<?php
require_once __DIR__ . '/../../includes/session.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/db.php';
requireLogin();
$config = require __DIR__ . '/../../includes/config.php';

$orders = [];
$result = $mysqli->query('SELECT id, buyer_name, product_name, quantity, total_price, status, ordered_at FROM orders ORDER BY ordered_at DESC');
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $orders[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders - <?= htmlspecialchars($config['app_name']) ?></title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="stylesheet" href="../../assets/css/responsive.css">
</head>
<body>
    <?php include __DIR__ . '/../../includes/navbar.php'; ?>
    <main class="dashboard-page">
        <section class="dashboard-section">
            <h1>Marketplace Orders</h1>
            <?php if (empty($orders)) : ?>
                <div class="alert-item alert-info">No orders have been placed yet. Your marketplace listings will receive orders here.</div>
            <?php else : ?>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Buyer</th>
                            <th>Product</th>
                            <th>Qty</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($orders as $order) : ?>
                            <tr>
                                <td><?= htmlspecialchars($order['buyer_name']) ?></td>
                                <td><?= htmlspecialchars($order['product_name']) ?></td>
                                <td><?= htmlspecialchars($order['quantity']) ?></td>
                                <td>Rs. <?= htmlspecialchars($order['total_price']) ?></td>
                                <td><?= htmlspecialchars($order['status']) ?></td>
                                <td><?= htmlspecialchars(date('M d, Y', strtotime($order['ordered_at']))) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </section>
    </main>
</body>
</html>
