<?php
require_once __DIR__ . '/../../includes/session.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/db.php';
requireLogin();
$config = require __DIR__ . '/../../includes/config.php';
$userId = $_SESSION['user_id'];

$groupId = intval($_GET['id'] ?? 0);
if ($groupId <= 0) {
    header('Location: groups.php');
    exit;
}

$group = null;
$stmt = $mysqli->prepare('SELECT ug.id, ug.name, ug.description, ug.land_mode, ug.owner_user_id, ug.owner_role, ug.created_at FROM user_groups ug JOIN group_members gm ON gm.group_id = ug.id WHERE ug.id = ? AND gm.user_id = ?');
$stmt->bind_param('ii', $groupId, $userId);
$stmt->execute();
$result = $stmt->get_result();
if ($result) {
    $group = $result->fetch_assoc();
}
if (!$group) {
    header('Location: groups.php');
    exit;
}

$error = '';
$success = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action']) && $_POST['action'] === 'invite_member') {
        $inviteUserId = intval($_POST['invite_user_id'] ?? 0);
        $memberRole = $_POST['member_role'] ?? 'farmer';
        $validRoles = ['farmer', 'expert', 'market_member', 'assistant'];

        if ($inviteUserId <= 0) {
            $error = 'Enter a valid user ID to invite.';
        } elseif (!in_array($memberRole, $validRoles, true)) {
            $error = 'Select a valid member role.';
        } else {
            $existsStmt = $mysqli->prepare('SELECT id FROM group_members WHERE group_id = ? AND user_id = ?');
            $existsStmt->bind_param('ii', $groupId, $inviteUserId);
            $existsStmt->execute();
            $existsResult = $existsStmt->get_result();
            if ($existsResult && $existsResult->num_rows > 0) {
                $error = 'This user is already a member of the group.';
            } else {
                $insertStmt = $mysqli->prepare('INSERT INTO group_members (group_id, user_id, role, joined_at) VALUES (?, ?, ?, NOW())');
                $insertStmt->bind_param('iis', $groupId, $inviteUserId, $memberRole);
                if ($insertStmt->execute()) {
                    $success = 'Member invited successfully.';
                } else {
                    $error = 'Unable to add the member. Check the user ID and try again.';
                }
            }
        }
    }

    if (isset($_POST['action']) && $_POST['action'] === 'add_item') {
        $title = trim($_POST['title'] ?? '');
        $itemType = $_POST['item_type'] ?? 'product';
        $category = trim($_POST['category'] ?? '');
        $price = $_POST['price'] !== '' ? floatval($_POST['price']) : null;
        $description = trim($_POST['description'] ?? '');
        $validTypes = ['product', 'service'];

        if (!$title) {
            $error = 'Enter a title for the product or service.';
        } elseif (!in_array($itemType, $validTypes, true)) {
            $error = 'Select a valid item type.';
        } else {
            $insertStmt = $mysqli->prepare('INSERT INTO group_items (group_id, user_id, title, item_type, category, price, description, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, NOW())');
            $insertStmt->bind_param('iisssds', $groupId, $userId, $title, $itemType, $category, $price, $description);
            if ($insertStmt->execute()) {
                $success = 'Item added to the group.';
                $_POST = [];
            } else {
                $error = 'Unable to add the item. Please try again.';
            }
        }
    }
}

$members = [];
$memberStmt = $mysqli->prepare('SELECT gm.id, gm.role, gm.joined_at, u.id AS user_id, u.name, u.email FROM group_members gm JOIN users u ON u.id = gm.user_id WHERE gm.group_id = ? ORDER BY gm.joined_at ASC');
$memberStmt->bind_param('i', $groupId);
$memberStmt->execute();
$memberResult = $memberStmt->get_result();
if ($memberResult) {
    while ($row = $memberResult->fetch_assoc()) {
        $members[] = $row;
    }
}

$items = [];
$itemStmt = $mysqli->prepare('SELECT gi.id, gi.title, gi.item_type, gi.category, gi.price, gi.description, gi.created_at, u.name AS owner_name FROM group_items gi JOIN users u ON u.id = gi.user_id WHERE gi.group_id = ? ORDER BY gi.created_at DESC');
$itemStmt->bind_param('i', $groupId);
$itemStmt->execute();
$itemResult = $itemStmt->get_result();
if ($itemResult) {
    while ($row = $itemResult->fetch_assoc()) {
        $items[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($group['name']) ?> - Groups - <?= htmlspecialchars($config['app_name']) ?></title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="stylesheet" href="../../assets/css/responsive.css">
</head>
<body>
    <?php include __DIR__ . '/../../includes/navbar.php'; ?>
    <main class="dashboard-page">
        <section class="dashboard-section">
            <div class="section-header">
                <div>
                    <a class="btn-secondary" href="groups.php">&larr; Back to Groups</a>
                    <h1><?= htmlspecialchars($group['name']) ?></h1>
                </div>
                <a class="secondary-link" href="groups.php">View all groups</a>
            </div>
            <p><?= nl2br(htmlspecialchars($group['description'])) ?></p>
            <div class="group-actions" style="margin-bottom: 20px;">
                <a class="btn-secondary" href="../marketplace/products.php?group_id=<?= $groupId ?>">Open group marketplace</a>
            </div>
            <div class="dashboard-grid stats-grid">
                <article class="dashboard-card">
                    <h3>Land Option</h3>
                    <p><?= htmlspecialchars(ucfirst($group['land_mode'])) ?></p>
                </article>
                <article class="dashboard-card">
                    <h3>Owner Role</h3>
                    <p><?= htmlspecialchars(ucfirst(str_replace('_', ' ', $group['owner_role']))) ?></p>
                </article>
                <article class="dashboard-card">
                    <h3>Group Created</h3>
                    <p><?= htmlspecialchars(date('M d, Y', strtotime($group['created_at']))) ?></p>
                </article>
            </div>

            <?php if ($success) : ?>
                <div class="alert-item alert-info"><?= htmlspecialchars($success) ?></div>
            <?php endif; ?>
            <?php if ($error) : ?>
                <div class="alert-item alert-high"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <div class="dashboard-grid action-grid">
                <article class="dashboard-card">
                    <h2>Invite Member</h2>
                    <form method="post" class="contact-form">
                        <input type="hidden" name="action" value="invite_member">
                        <div class="form-group">
                            <label for="invite_user_id">User ID</label>
                            <input type="number" id="invite_user_id" name="invite_user_id" required value="<?= htmlspecialchars($_POST['invite_user_id'] ?? '') ?>">
                        </div>
                        <div class="form-group">
                            <label for="member_role">Role</label>
                            <select id="member_role" name="member_role">
                                <option value="farmer" <?= ($_POST['member_role'] ?? '') === 'farmer' ? 'selected' : '' ?>>Farmer</option>
                                <option value="expert" <?= ($_POST['member_role'] ?? '') === 'expert' ? 'selected' : '' ?>>Expert</option>
                                <option value="market_member" <?= ($_POST['member_role'] ?? '') === 'market_member' ? 'selected' : '' ?>>Market Member</option>
                                <option value="assistant" <?= ($_POST['member_role'] ?? '') === 'assistant' ? 'selected' : '' ?>>Assistant</option>
                            </select>
                        </div>
                        <div class="form-actions">
                            <button type="submit" class="btn-primary">Invite Member</button>
                        </div>
                    </form>
                </article>
                <article class="dashboard-card">
                    <h2>Add Group Item</h2>
                    <form method="post" class="contact-form">
                        <input type="hidden" name="action" value="add_item">
                        <div class="form-group">
                            <label for="title">Title</label>
                            <input type="text" id="title" name="title" required value="<?= htmlspecialchars($_POST['title'] ?? '') ?>">
                        </div>
                        <div class="form-group">
                            <label for="item_type">Type</label>
                            <select id="item_type" name="item_type">
                                <option value="product" <?= ($_POST['item_type'] ?? '') === 'product' ? 'selected' : '' ?>>Product</option>
                                <option value="service" <?= ($_POST['item_type'] ?? '') === 'service' ? 'selected' : '' ?>>Service</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="category">Category</label>
                            <input type="text" id="category" name="category" value="<?= htmlspecialchars($_POST['category'] ?? '') ?>">
                        </div>
                        <div class="form-group">
                            <label for="price">Price (optional)</label>
                            <input type="number" id="price" name="price" step="0.01" value="<?= htmlspecialchars($_POST['price'] ?? '') ?>">
                        </div>
                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea id="description" name="description" rows="4"><?= htmlspecialchars($_POST['description'] ?? '') ?></textarea>
                        </div>
                        <div class="form-actions">
                            <button type="submit" class="btn-primary">Add Item</button>
                        </div>
                    </form>
                </article>
            </div>

            <section class="dashboard-section">
                <h2>Members</h2>
                <?php if (empty($members)) : ?>
                    <div class="alert-item alert-info">No members have joined this group yet.</div>
                <?php else : ?>
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>User</th>
                                <th>Role</th>
                                <th>Email</th>
                                <th>Joined</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($members as $member) : ?>
                                <tr>
                                    <td><?= htmlspecialchars($member['name']) ?> (ID: <?= intval($member['user_id']) ?>)</td>
                                    <td><?= htmlspecialchars(ucfirst(str_replace('_', ' ', $member['role']))) ?></td>
                                    <td><?= htmlspecialchars($member['email']) ?></td>
                                    <td><?= htmlspecialchars(date('M d, Y', strtotime($member['joined_at']))) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </section>

            <section class="dashboard-section">
                <h2>Shared Items</h2>
                <?php if (empty($items)) : ?>
                    <div class="alert-item alert-info">No products or services have been shared in this group yet.</div>
                <?php else : ?>
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Type</th>
                                <th>Category</th>
                                <th>Price</th>
                                <th>Owner</th>
                                <th>Added</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($items as $item) : ?>
                                <tr>
                                    <td><?= htmlspecialchars($item['title']) ?></td>
                                    <td><?= htmlspecialchars(ucfirst($item['item_type'])) ?></td>
                                    <td><?= htmlspecialchars($item['category'] ?: '—') ?></td>
                                    <td><?= $item['price'] !== null ? 'Rs. ' . htmlspecialchars(number_format($item['price'], 2)) : 'Free' ?></td>
                                    <td><?= htmlspecialchars($item['owner_name']) ?></td>
                                    <td><?= htmlspecialchars(date('M d, Y', strtotime($item['created_at']))) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </section>
        </section>
    </main>
</body>
</html>
