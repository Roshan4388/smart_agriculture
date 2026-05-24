<?php
require_once __DIR__ . '/../../includes/session.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/db.php';
requireLogin();
$config = require __DIR__ . '/../../includes/config.php';
$user = getCurrentUser($mysqli);

$error = '';
$success = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $landMode = $_POST['land_mode'] ?? 'own';
    $validLandModes = ['own', 'virtual'];

    if (!$name) {
        $error = 'Please enter a group name.';
    } elseif (!in_array($landMode, $validLandModes, true)) {
        $error = 'Please select a valid land option.';
    } else {
        $ownerRole = 'farmer';
        if (!empty($user['experience'])) {
            $ownerRole = 'expert';
        } elseif ($user['role'] === 'admin') {
            $ownerRole = 'assistant';
        }

        $stmt = $mysqli->prepare('INSERT INTO user_groups (name, owner_user_id, owner_role, description, land_mode, created_at) VALUES (?, ?, ?, ?, ?, NOW())');
        $stmt->bind_param('sisss', $name, $_SESSION['user_id'], $ownerRole, $description, $landMode);
        if ($stmt->execute()) {
            $groupId = $stmt->insert_id;
            $memberStmt = $mysqli->prepare('INSERT INTO group_members (group_id, user_id, role, joined_at) VALUES (?, ?, ?, NOW())');
            $memberStmt->bind_param('iis', $groupId, $_SESSION['user_id'], $ownerRole);
            $memberStmt->execute();
            $success = 'Group created successfully. You can now invite members and add group products or services.';
            $_POST = [];
        } else {
            $error = 'Unable to create the group. Please try again.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Group - <?= htmlspecialchars($config['app_name']) ?></title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="stylesheet" href="../../assets/css/responsive.css">
</head>
<body>
    <?php include __DIR__ . '/../../includes/navbar.php'; ?>
    <main class="dashboard-page">
        <section class="dashboard-section">
            <h1>Create Collaboration Group</h1>
            <p>Create a shared group for farmers, experts, market members or assistants. Choose whether the group uses your own land or operates in a virtual space.</p>
            <?php if ($success) : ?>
                <div class="alert-item alert-info"><?= htmlspecialchars($success) ?></div>
            <?php endif; ?>
            <?php if ($error) : ?>
                <div class="alert-item alert-high"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
            <form method="post" action="create-group.php" class="contact-form">
                <div class="form-group">
                    <label for="name">Group Name</label>
                    <input type="text" id="name" name="name" required value="<?= htmlspecialchars($_POST['name'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea id="description" name="description" rows="4"><?= htmlspecialchars($_POST['description'] ?? '') ?></textarea>
                </div>
                <div class="form-group">
                    <label for="land_mode">Land Option</label>
                    <select id="land_mode" name="land_mode">
                        <option value="own" <?= ($_POST['land_mode'] ?? '') === 'own' ? 'selected' : '' ?>>Own Land</option>
                        <option value="virtual" <?= ($_POST['land_mode'] ?? '') === 'virtual' ? 'selected' : '' ?>>Virtual Land</option>
                    </select>
                </div>
                <div class="form-actions">
                    <button type="submit" class="btn-primary">Create Group</button>
                </div>
            </form>
        </section>
    </main>
</body>
</html>
