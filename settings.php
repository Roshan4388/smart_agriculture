<?php
require_once __DIR__ . '/includes/session.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/db.php';
requireLogin();
$config = require __DIR__ . '/includes/config.php';
$user = getCurrentUser($mysqli);

$feedback = '';
$updated = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['section'])) {
    if ($_POST['section'] === 'account') {
        $name = trim($_POST['name'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $address = trim($_POST['address'] ?? '');
        $experience = trim($_POST['experience'] ?? '');

        if ($name !== '') {
            $stmt = $mysqli->prepare('UPDATE users SET name = ?, phone = ?, address = ?, experience = ? WHERE id = ?');
            $stmt->bind_param('ssssi', $name, $phone, $address, $experience, $_SESSION['user_id']);
            if ($stmt->execute()) {
                $feedback = 'Account settings updated successfully.';
                $updated = true;
                $user['name'] = $name;
                $user['phone'] = $phone;
                $user['address'] = $address;
                $user['experience'] = $experience;
                $_SESSION['user_name'] = $name;
            } else {
                $feedback = 'Unable to save account settings at this time.';
            }
        } else {
            $feedback = 'Please enter your name to save account settings.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings - <?= htmlspecialchars($config['app_name']) ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/responsive.css">
    <style>
        .settings-grid {
            display: grid;
            grid-template-columns: minmax(260px, 320px) minmax(0, 1fr);
            gap: 24px;
            margin-top: 24px;
        }

        .settings-menu {
            display: grid;
            gap: 14px;
        }

        .settings-menu a,
        .settings-menu button {
            display: block;
            width: 100%;
            text-align: left;
            padding: 16px 18px;
            border-radius: 16px;
            border: 1px solid rgba(28, 40, 52, 0.15);
            background: #ffffff;
            color: #14282d;
            font-weight: 700;
            text-decoration: none;
            transition: transform 0.25s ease, box-shadow 0.25s ease, background 0.25s ease;
            cursor: pointer;
        }

        .settings-menu a:hover,
        .settings-menu button:hover {
            transform: translateX(2px);
            box-shadow: 0 16px 28px rgba(34, 84, 97, 0.08);
            background: #f3fcff;
        }

        .settings-section {
            background: rgba(255, 255, 255, 0.92);
            border: 1px solid rgba(28, 40, 52, 0.08);
            border-radius: 20px;
            padding: 24px;
            box-shadow: 0 16px 30px rgba(16, 58, 72, 0.06);
        }

        .settings-section+.settings-section {
            margin-top: 20px;
        }

        .settings-section h2 {
            margin-top: 0;
            font-size: 1.25rem;
        }

        .settings-card {
            margin-bottom: 16px;
        }

        .settings-card p {
            margin: 0;
            color: #526a70;
            line-height: 1.7;
        }

        .settings-form {
            display: grid;
            gap: 16px;
            margin-top: 16px;
        }

        .settings-form label {
            display: block;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .settings-form input,
        .settings-form select,
        .settings-form textarea {
            width: 100%;
            padding: 12px 14px;
            border: 1px solid #c6d3d9;
            border-radius: 10px;
            background: #fbfeff;
            font-size: 0.95rem;
            color: #1d3338;
            box-sizing: border-box;
        }

        .settings-form textarea {
            min-height: 120px;
            resize: vertical;
        }

        .settings-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            margin-top: 8px;
        }

        .settings-actions .btn-primary,
        .settings-actions .btn-secondary {
            width: auto;
            min-width: 160px;
        }

        .settings-note {
            color: #2d6a4f;
            background: #eef9f2;
            border: 1px solid #c6e8cf;
            padding: 12px 14px;
            border-radius: 12px;
            margin-top: 16px;
        }

        .settings-alert {
            margin-top: 20px;
        }

        @media (max-width: 920px) {
            .settings-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>
    <?php include __DIR__ . '/includes/navbar.php'; ?>
    <main class="dashboard-page">
        <section class="dashboard-section">
            <div class="section-header">
                <h1>Settings</h1>
                <p>Configure your Smart Agriculture dashboard and farm preferences from one place.</p>
            </div>
            <?php if ($feedback) : ?>
                <div class="alert-item alert-info settings-alert"><?= htmlspecialchars($feedback) ?></div>
            <?php endif; ?>
            <div class="settings-grid">
                <div class="settings-menu">
                    <a href="#appearance">Appearance</a>
                    <a href="#account">Account</a>
                    <a href="#language">Language</a>
                    <a href="#notifications">Notifications</a>
                    <a href="#security">Security</a>
                    <a href="#farm-preferences">Farm Preferences</a>
                    <a href="#switch-user">Switch User</a>
                    <a href="#help-support">Help & Support</a>
                    <a class="btn-secondary" href="logout.php">Logout</a>
                </div>

                <div>
                    <section id="appearance" class="settings-section">
                        <h2>Appearance</h2>
                        <div class="settings-card">
                            <p>Choose your dashboard style and theme. Use the global dark/light toggle in the sidebar for quick switching.</p>
                        </div>
                        <form class="settings-form" method="post" action="settings.php">
                            <input type="hidden" name="section" value="appearance">
                            <label for="theme">Theme</label>
                            <select id="theme" name="theme">
                                <option value="auto">Auto (system)</option>
                                <option value="light">Light</option>
                                <option value="dark">Dark</option>
                                <option value="green">Green farm theme</option>
                            </select>
                            <label for="layout">Dashboard layout</label>
                            <select id="layout" name="layout">
                                <option value="compact">Compact</option>
                                <option value="standard" selected>Standard</option>
                                <option value="expanded">Expanded</option>
                            </select>
                            <div class="settings-actions">
                                <button type="button" class="btn-secondary">Preview theme</button>
                                <button type="submit" class="btn-primary">Save appearance</button>
                            </div>
                        </form>
                    </section>

                    <section id="account" class="settings-section">
                        <h2>Account</h2>
                        <p>Update your profile details for the farmer account.</p>
                        <form class="settings-form" method="post" action="settings.php">
                            <input type="hidden" name="section" value="account">
                            <label for="name">Name</label>
                            <input id="name" name="name" type="text" value="<?= htmlspecialchars($user['name'] ?? '') ?>" required>

                            <label for="phone">Phone</label>
                            <input id="phone" name="phone" type="text" value="<?= htmlspecialchars($user['phone'] ?? '') ?>">

                            <label for="address">Address</label>
                            <input id="address" name="address" type="text" value="<?= htmlspecialchars($user['address'] ?? '') ?>">

                            <label for="experience">Farming experience</label>
                            <input id="experience" name="experience" type="text" value="<?= htmlspecialchars($user['experience'] ?? '') ?>">

                            <div class="settings-actions">
                                <button type="submit" class="btn-primary">Save account</button>
                            </div>
                        </form>
                    </section>

                    <section id="language" class="settings-section">
                        <h2>Language</h2>
                        <p>Select your preferred interface language.</p>
                        <form class="settings-form" method="post" action="settings.php">
                            <input type="hidden" name="section" value="language">
                            <label for="language-select">Preferred language</label>
                            <select id="language-select" name="language">
                                <?php foreach ($config['supported_languages'] as $code => $label) : ?>
                                    <option value="<?= htmlspecialchars($code) ?>"><?= htmlspecialchars($label) ?></option>
                                <?php endforeach; ?>
                            </select>
                            <div class="settings-actions">
                                <button type="submit" class="btn-primary">Save language</button>
                            </div>
                        </form>
                    </section>

                    <section id="notifications" class="settings-section">
                        <h2>Notifications</h2>
                        <p>Turn notifications on or off for system alerts, weather warnings, and market updates.</p>
                        <div class="settings-form">
                            <label><input type="checkbox" checked> Weather alerts</label>
                            <label><input type="checkbox" checked> Market updates</label>
                            <label><input type="checkbox"> Expert replies</label>
                            <label><input type="checkbox"> Security notifications</label>
                            <div class="settings-actions">
                                <button type="button" class="btn-primary">Save notifications</button>
                            </div>
                        </div>
                    </section>

                    <section id="security" class="settings-section">
                        <h2>Security</h2>
                        <p>Protect your account with password and two-factor settings.</p>
                        <div class="settings-form">
                            <label for="current-password">Current password</label>
                            <input id="current-password" type="password" placeholder="••••••••">
                            <label for="new-password">New password</label>
                            <input id="new-password" type="password" placeholder="••••••••">
                            <label for="confirm-password">Confirm password</label>
                            <input id="confirm-password" type="password" placeholder="••••••••">
                            <div class="settings-actions">
                                <button type="button" class="btn-secondary">Change password</button>
                                <button type="button" class="btn-secondary">Enable 2FA</button>
                            </div>
                        </div>
                    </section>

                    <section id="farm-preferences" class="settings-section">
                        <h2>Farm Preferences</h2>
                        <p>Customize farm defaults such as crop alerts, land mode, and notification preferences.</p>
                        <form class="settings-form" method="post" action="settings.php">
                            <input type="hidden" name="section" value="farm_preferences">
                            <label for="land-mode">Land mode</label>
                            <select id="land-mode" name="land_mode">
                                <option value="own">Own farm</option>
                                <option value="virtual">Virtual planning</option>
                            </select>

                            <label for="crop-alerts">Crop alert level</label>
                            <select id="crop-alerts" name="crop_alerts">
                                <option value="normal">Normal</option>
                                <option value="enhanced">Enhanced</option>
                                <option value="critical">Critical</option>
                            </select>

                            <label for="preferred-crops">Preferred crops</label>
                            <input id="preferred-crops" name="preferred_crops" type="text" placeholder="e.g. Rice, Wheat, Vegetables">
                            <div class="settings-actions">
                                <button type="submit" class="btn-primary">Save farm preferences</button>
                            </div>
                        </form>
                    </section>

                    <section id="switch-user" class="settings-section">
                        <h2>Switch User</h2>
                        <p>Sign out here and log in as another farmer, expert, or administrator.</p>
                        <div class="settings-actions">
                            <a class="btn-secondary" href="sign-in.php">Switch user</a>
                            <a class="btn-primary" href="logout.php">Logout now</a>
                        </div>
                    </section>

                    <section id="help-support" class="settings-section">
                        <h2>Help & Support</h2>
                        <p>Access support, documentation, and FAQs for the Smart Agriculture platform.</p>
                        <div class="settings-form">
                            <label for="support-message">Send a support request</label>
                            <textarea id="support-message" placeholder="Describe the issue or question..."></textarea>
                            <div class="settings-actions">
                                <button type="button" class="btn-primary">Send request</button>
                            </div>
                        </div>
                        <div class="settings-note">
                            Need faster help? Contact your administrator or use the expert consult page for farm advice.
                        </div>
                    </section>
                </div>
            </div>
        </section>
    </main>
    <footer class="site-footer">
        <p>&copy; <?= date('Y') ?> <?= htmlspecialchars($config['app_name']) ?>.</p>
    </footer>
    <script src="assets/js/site.js"></script>
</body>

</html>