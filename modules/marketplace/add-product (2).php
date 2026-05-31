<?php
require_once __DIR__ . '/../../includes/session.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/db.php';
requireLogin();
$config = require __DIR__ . '/../../includes/config.php';

$error = '';
$success = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $category = trim($_POST['category'] ?? '');
    $price = floatval($_POST['price'] ?? 0);
    $quantity = intval($_POST['quantity'] ?? 0);
    $description = trim($_POST['description'] ?? '');

    if (!$title || !$category || $price <= 0 || $quantity <= 0) {
        $error = 'Please provide valid product details.';
    } else {
        $stmt = $mysqli->prepare('INSERT INTO products (title, category, price, quantity, description, created_at) VALUES (?, ?, ?, ?, ?, NOW())');
        $stmt->bind_param('ssdis', $title, $category, $price, $quantity, $description);
        if ($stmt->execute()) {
            $success = 'Product added successfully.';
            $_POST = [];
        } else {
            $error = 'Unable to save the product. Please try again.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product - <?= htmlspecialchars($config['app_name']) ?></title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="stylesheet" href="../../assets/css/responsive.css">
</head>
<body>
    <?php include __DIR__ . '/../../includes/navbar.php'; ?>
    <?php
    $groupId = intval($_GET['group_id'] ?? 0);
    $backLink = $groupId ? 'products.php?group_id=' . $groupId : 'products.php';
    ?>
    <main class="dashboard-page">
        <section class="dashboard-section">
            <div class="section-header">
                <div>
                    <a class="btn-secondary" href="<?= htmlspecialchars($backLink) ?>">&larr; Back to Products</a>
                    <h1>Add Marketplace Product</h1>
                </div>
            </div>
            <p>Post a produce listing to connect with buyers and manage orders from the marketplace.</p>
            <?php if ($success) : ?>
                <div class="alert-item alert-info"><?= htmlspecialchars($success) ?></div>
            <?php endif; ?>
            <?php if ($error) : ?>
                <div class="alert-item alert-high"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
            <form method="post" action="add-product.php<?= $groupId ? '?group_id=' . $groupId : '' ?>" class="contact-form">
                <div class="form-group">
                    <label for="title">Product title</label>
                    <input type="text" id="title" name="title" required value="<?= htmlspecialchars($_POST['title'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label for="category">Category</label>
                    <input type="text" id="category" name="category" required value="<?= htmlspecialchars($_POST['category'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label for="price">Price (Rs.)</label>
                    <input type="number" id="price" name="price" step="0.01" required value="<?= htmlspecialchars($_POST['price'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label for="quantity">Quantity</label>
                    <input type="number" id="quantity" name="quantity" required value="<?= htmlspecialchars($_POST['quantity'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea id="description" name="description" rows="4"><?= htmlspecialchars($_POST['description'] ?? '') ?></textarea>
                </div>
                <div class="form-actions">
                    <button type="submit" class="btn-primary">Save Product</button>
                </div>
            </form>
        </section>
    </main>
</body>
</html>
