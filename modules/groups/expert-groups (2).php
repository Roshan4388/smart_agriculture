<?php
require_once __DIR__ . '/../../includes/session.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/db.php';
requireLogin();
$config = require __DIR__ . '/../../includes/config.php';

$groups = [];
$stmt = $mysqli->prepare(
    'SELECT ug.id, ug.name, ug.description, ug.land_mode, ug.owner_role, ug.created_at, COUNT(gm.id) AS member_count
     FROM user_groups ug
     LEFT JOIN group_members gm ON gm.group_id = ug.id
     WHERE ug.owner_role = ?
     GROUP BY ug.id
     ORDER BY ug.created_at DESC'
);
$ownerRole = 'expert';
$stmt->bind_param('s', $ownerRole);
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
    <title>Expert Groups - <?= htmlspecialchars($config['app_name']) ?></title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="stylesheet" href="../../assets/css/responsive.css">
</head>

<body>
    <?php include __DIR__ . '/../../includes/navbar.php'; ?>
    <main class="dashboard-page">
        <section class="dashboard-section">
            <div class="section-header">
                <div>
                    <span class="eyebrow">Expert Group</span>
                    <h1>Expert-led Groups</h1>
                </div>
                <a class="secondary-link" href="groups.php">Back to All Groups</a>
            </div>
            <p>These groups are managed by agriculture experts and advisors. Join to get specialized support from experienced farm professionals.</p>
            <?php if (empty($groups)) : ?>
                <div class="alert-item alert-info">No expert groups are available at the moment.</div>
            <?php else : ?>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Land Option</th>
                            <th>Members</th>
                            <th>Created</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($groups as $group) : ?>
                            <tr>
                                <td><?= htmlspecialchars($group['name']) ?></td>
                                <td><?= htmlspecialchars($group['description']) ?></td>
                                <td><?= htmlspecialchars(ucfirst($group['land_mode'])) ?></td>
                                <td><?= htmlspecialchars($group['member_count']) ?></td>
                                <td><?= htmlspecialchars(date('M d, Y', strtotime($group['created_at']))) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </section>
    </main>
</body>

</html>