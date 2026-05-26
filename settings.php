<?php
require_once __DIR__ . '/includes/session.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/db.php';
requireLogin();
$config = require __DIR__ . '/includes/config.php';
$user = getCurrentUser($mysqli);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings - <?= htmlspecialchars($config['app_name']) ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/responsive.css">
</head>
<body>
    <?php include __DIR__ . '/includes/navbar.php'; ?>
    <main class="dashboard-page">
        <section class="dashboard-section">
            <div class="section-header">
                <h1>Settings</h1>
                <p>Choose your theme and customize the dashboard behavior for farming workflows.</p>
            </div>
            <div class="dashboard-grid feature-grid">
                <article class="dashboard-card feature-card">
                    <h3>Suggested Theme</h3>
                    <p>Green → Agriculture<br>Blue → Water<br>Brown → Soil<br>White → Clean background.</p>
                </article>
                <article class="dashboard-card feature-card">
                    <h3>Display Mode</h3>
                    <p>Toggle between light and dark mode using the button in the sidebar or header.</p>
                </article>
                <article class="dashboard-card feature-card">
                    <h3>Update Frequency</h3>
                    <p>Real-time updates every few seconds keep sensor panels fresh.</p>
                </article>
            </div>
        </section>
    </main>
    <footer class="site-footer"><p>&copy; <?= date('Y') ?> <?= htmlspecialchars($config['app_name']) ?>.</p></footer>
    <script src="assets/js/site.js"></script>
</body>
</html>
