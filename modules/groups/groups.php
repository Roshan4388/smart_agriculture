<?php
require_once __DIR__ . '/../../includes/session.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/db.php';
requireLogin();
$config = require __DIR__ . '/../../includes/config.php';

$userId = $_SESSION['user_id'];
$groups = [];
$stmt = $mysqli->prepare('SELECT ug.id, ug.name, ug.description, ug.land_mode, ug.owner_role, ug.created_at FROM user_groups ug JOIN group_members gm ON gm.group_id = ug.id WHERE gm.user_id = ? ORDER BY ug.created_at DESC');
$stmt->bind_param('i', $userId);
$stmt->execute();
$result = $stmt->get_result();
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $groups[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Groups - <?= htmlspecialchars($config['app_name']) ?></title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="stylesheet" href="../../assets/css/responsive.css">
</head>
<body>
    <?php include __DIR__ . '/../../includes/navbar.php'; ?>
    <main class="dashboard-page">
        <section class="dashboard-section">
            <div class="section-header">
                <h1>Groups</h1>
                <a class="secondary-link" href="create-group.php">Create New Group</a>
            </div>
            <p>Use groups to collaborate with farmers, experts, market members, and assistants. Share products, services, and land options in one place.</p>
            <?php if (empty($groups)) : ?>
                <div class="alert-item alert-info">You are not part of any groups yet. Create one to start collaborating.</div>
            <?php else : ?>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Group Name</th>
                            <th>Land Type</th>
                            <th>Owner Role</th>
                            <th>Created</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($groups as $group) : ?>
                            <tr>
                                <td><?= htmlspecialchars($group['name']) ?></td>
                                <td><?= htmlspecialchars(ucfirst($group['land_mode'])) ?></td>
                                <td><?= htmlspecialchars(ucfirst(str_replace('_', ' ', $group['owner_role']))) ?></td>
                                <td><?= htmlspecialchars(date('M d, Y', strtotime($group['created_at']))) ?></td>
                                <td><a href="group-details.php?id=<?= intval($group['id']) ?>">Open</a></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </section>
    </main>
</body>
</html>
