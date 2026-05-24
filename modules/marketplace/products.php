<?php
require_once __DIR__ . '/../../includes/session.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/db.php';
requireLogin();
$config = require __DIR__ . '/../../includes/config.php';

$products = [];
$result = $mysqli->query('SELECT id, title, category, price, quantity, created_at FROM products ORDER BY created_at DESC');
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Marketplace Products - <?= htmlspecialchars($config['app_name']) ?></title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="stylesheet" href="../../assets/css/responsive.css">
</head>
<body>
    <?php include __DIR__ . '/../../includes/navbar.php'; ?>
    <?php
    $backLink = '';
    if (!empty($_GET['group_id'])) {
        $groupId = intval($_GET['group_id']);
        $backLink = '../groups/group-details.php?id=' . $groupId;
    }
    ?>
    <main class="dashboard-page">
        <section class="dashboard-section">
            <div class="section-header">
                <div>
                    <?php if ($backLink) : ?>
                        <a class="btn-secondary" href="<?= htmlspecialchars($backLink) ?>">&larr; Back to Group</a>
                    <?php endif; ?>
                    <h1>Marketplace Products</h1>
                </div>
                <a class="secondary-link" href="add-product.php<?= $backLink ? '?group_id=' . intval($_GET['group_id']) : '' ?>">Add New Product</a>
            </div>
            <?php if (empty($products)) : ?>
                <div class="alert-item alert-info">No products are available yet. Add a listing to start selling your produce.</div>
            <?php else : ?>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Category</th>
                            <th>Price</th>
                            <th>Qty</th>
                            <th>Posted</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($products as $product) : ?>
                            <tr>
                                <td><?= htmlspecialchars($product['title']) ?></td>
                                <td><?= htmlspecialchars($product['category']) ?></td>
                                <td>Rs. <?= htmlspecialchars($product['price']) ?></td>
                                <td><?= htmlspecialchars($product['quantity']) ?></td>
                                <td><?= htmlspecialchars(date('M d, Y', strtotime($product['created_at']))) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </section>
    </main>
</body>
</html>
