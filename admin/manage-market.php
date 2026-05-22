<?php
require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';
requireAdmin();
$config = require __DIR__ . '/../includes/config.php';

$products = [];
$productResult = $mysqli->query('SELECT id, title, category, price, quantity, created_at FROM products ORDER BY created_at DESC');
if ($productResult) {
    while ($row = $productResult->fetch_assoc()) {
        $products[] = $row;
    }
}
$orders = [];
$orderResult = $mysqli->query('SELECT id, buyer_name, product_name, quantity, total_price, status, ordered_at FROM orders ORDER BY ordered_at DESC LIMIT 10');
if ($orderResult) {
    while ($row = $orderResult->fetch_assoc()) {
        $orders[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Marketplace - <?= htmlspecialchars($config['app_name']) ?></title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/responsive.css">
</head>
<body>
    <?php include __DIR__ . '/../includes/navbar.php'; ?>
    <main class="dashboard-page">
        <section class="dashboard-section">
            <h1>Marketplace Management</h1>
            <p>Review active marketplace listings and recent order activity.</p>
            <h2>Products</h2>
            <table class="data-table">
                <thead><tr><th>Title</th><th>Category</th><th>Price</th><th>Qty</th></tr></thead>
                <tbody>
                    <?php foreach ($products as $product) : ?>
                        <tr>
                            <td><?= htmlspecialchars($product['title']) ?></td>
                            <td><?= htmlspecialchars($product['category']) ?></td>
                            <td>Rs. <?= htmlspecialchars($product['price']) ?></td>
                            <td><?= htmlspecialchars($product['quantity']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <h2>Recent Orders</h2>
            <table class="data-table">
                <thead><tr><th>Buyer</th><th>Product</th><th>Qty</th><th>Status</th></tr></thead>
                <tbody>
                    <?php foreach ($orders as $order) : ?>
                        <tr>
                            <td><?= htmlspecialchars($order['buyer_name']) ?></td>
                            <td><?= htmlspecialchars($order['product_name']) ?></td>
                            <td><?= htmlspecialchars($order['quantity']) ?></td>
                            <td><?= htmlspecialchars($order['status']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>
    </main>
</body>
</html>
