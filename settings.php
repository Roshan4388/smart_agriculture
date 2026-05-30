<?php
require_once __DIR__ . '/includes/session.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/db.php';
requireLogin();
$config = require __DIR__ . '/includes/config.php';
$user = getCurrentUser($mysqli);
$userId = (int) $_SESSION['user_id'];

$settingDefaults = [
    'theme' => 'auto',
    'layout' => 'standard',
    'language' => 'en',
    'notify_weather' => '1',
    'notify_market' => '1',
    'notify_expert' => '0',
    'notify_security' => '0',
    'two_factor_enabled' => '0',
    'land_mode' => 'own',
    'crop_alerts' => 'normal',
    'preferred_crops' => '',
];

function loadUserSettings(mysqli $mysqli, int $userId, array $defaults): array
{
    $settings = $defaults;
    $stmt = $mysqli->prepare('SELECT setting_key, setting_value FROM user_settings WHERE user_id = ?');
    $stmt->bind_param('i', $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        if (array_key_exists($row['setting_key'], $settings)) {
            $settings[$row['setting_key']] = (string) $row['setting_value'];
        }
    }
    return $settings;
}

function saveUserSetting(mysqli $mysqli, int $userId, string $key, string $value): bool
{
    $stmt = $mysqli->prepare(
        'INSERT INTO user_settings (user_id, setting_key, setting_value)
         VALUES (?, ?, ?)
         ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)'
    );
    $stmt->bind_param('iss', $userId, $key, $value);
    return $stmt->execute();
}

function saveUserSettings(mysqli $mysqli, int $userId, array $values): bool
{
    foreach ($values as $key => $value) {
        if (!saveUserSetting($mysqli, $userId, $key, (string) $value)) {
            return false;
        }
    }
    return true;
}

$settings = loadUserSettings($mysqli, $userId, $settingDefaults);

$feedback = '';
$updated = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['section'])) {
    $section = $_POST['section'];

    if ($section === 'appearance') {
        $allowedThemes = ['auto', 'light', 'dark', 'green'];
        $allowedLayouts = ['compact', 'standard', 'expanded'];
        $theme = in_array($_POST['theme'] ?? '', $allowedThemes, true) ? $_POST['theme'] : 'auto';
        $layout = in_array($_POST['layout'] ?? '', $allowedLayouts, true) ? $_POST['layout'] : 'standard';

        if (saveUserSettings($mysqli, $userId, ['theme' => $theme, 'layout' => $layout])) {
            $feedback = 'Appearance settings saved successfully.';
            $settings['theme'] = $theme;
            $settings['layout'] = $layout;
        } else {
            $feedback = 'Unable to save appearance settings at this time.';
        }
    } elseif ($section === 'account') {
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
    } elseif ($section === 'language') {
        $language = $_POST['language'] ?? 'en';
        if (!array_key_exists($language, $config['supported_languages'])) {
            $language = 'en';
        }

        if (saveUserSetting($mysqli, $userId, 'language', $language)) {
            $feedback = 'Language preference saved successfully.';
            $settings['language'] = $language;
        } else {
            $feedback = 'Unable to save language preference at this time.';
        }
    } elseif ($section === 'notifications') {
        $notificationSettings = [
            'notify_weather' => isset($_POST['notify_weather']) ? '1' : '0',
            'notify_market' => isset($_POST['notify_market']) ? '1' : '0',
            'notify_expert' => isset($_POST['notify_expert']) ? '1' : '0',
            'notify_security' => isset($_POST['notify_security']) ? '1' : '0',
        ];

        if (saveUserSettings($mysqli, $userId, $notificationSettings)) {
            $feedback = 'Notification settings saved successfully.';
            $settings = array_merge($settings, $notificationSettings);
        } else {
            $feedback = 'Unable to save notification settings at this time.';
        }
    } elseif ($section === 'security') {
        $currentPassword = $_POST['current_password'] ?? '';
        $newPassword = $_POST['new_password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';

        if (isset($_POST['enable_two_factor'])) {
            $twoFactorValue = $settings['two_factor_enabled'] === '1' ? '0' : '1';
            if (saveUserSetting($mysqli, $userId, 'two_factor_enabled', $twoFactorValue)) {
                $settings['two_factor_enabled'] = $twoFactorValue;
                $feedback = $twoFactorValue === '1' ? 'Two-factor preference enabled.' : 'Two-factor preference disabled.';
            } else {
                $feedback = 'Unable to update two-factor preference at this time.';
            }
        } elseif ($currentPassword === '' || $newPassword === '' || $confirmPassword === '') {
            $feedback = 'Please complete all password fields.';
        } elseif ($newPassword !== $confirmPassword) {
            $feedback = 'New password and confirmation do not match.';
        } elseif (strlen($newPassword) < 6) {
            $feedback = 'New password must be at least 6 characters.';
        } else {
            $stmt = $mysqli->prepare('SELECT password FROM users WHERE id = ? LIMIT 1');
            $stmt->bind_param('i', $userId);
            $stmt->execute();
            $passwordRow = $stmt->get_result()->fetch_assoc();

            if (!$passwordRow || !password_verify($currentPassword, $passwordRow['password'])) {
                $feedback = 'Current password is incorrect.';
            } else {
                $passwordHash = password_hash($newPassword, PASSWORD_DEFAULT);
                $stmt = $mysqli->prepare('UPDATE users SET password = ? WHERE id = ?');
                $stmt->bind_param('si', $passwordHash, $userId);
                $feedback = $stmt->execute() ? 'Password changed successfully.' : 'Unable to change password at this time.';
            }
        }
    } elseif ($section === 'farm_preferences') {
        $allowedLandModes = ['own', 'virtual'];
        $allowedCropAlerts = ['normal', 'enhanced', 'critical'];
        $landMode = in_array($_POST['land_mode'] ?? '', $allowedLandModes, true) ? $_POST['land_mode'] : 'own';
        $cropAlerts = in_array($_POST['crop_alerts'] ?? '', $allowedCropAlerts, true) ? $_POST['crop_alerts'] : 'normal';
        $preferredCrops = trim($_POST['preferred_crops'] ?? '');

        if (saveUserSettings($mysqli, $userId, [
            'land_mode' => $landMode,
            'crop_alerts' => $cropAlerts,
            'preferred_crops' => $preferredCrops,
        ])) {
            $feedback = 'Farm preferences saved successfully.';
            $settings['land_mode'] = $landMode;
            $settings['crop_alerts'] = $cropAlerts;
            $settings['preferred_crops'] = $preferredCrops;
        } else {
            $feedback = 'Unable to save farm preferences at this time.';
        }
    } elseif ($section === 'support') {
        $supportMessage = trim($_POST['support_message'] ?? '');
        if ($supportMessage === '') {
            $feedback = 'Please write a support message before sending.';
        } else {
            $stmt = $mysqli->prepare('INSERT INTO support_requests (user_id, message) VALUES (?, ?)');
            $stmt->bind_param('is', $userId, $supportMessage);
            $feedback = $stmt->execute() ? 'Support request sent successfully.' : 'Unable to send support request at this time.';
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
            grid-template-columns: minmax(210px, 260px) minmax(0, 1fr);
            gap: 20px;
            margin-top: 24px;
            align-items: start;
        }

        .settings-menu {
            display: grid;
            gap: 10px;
            position: sticky;
            top: 24px;
        }

        .settings-menu a,
        .settings-menu button {
            display: flex;
            align-items: center;
            width: 100%;
            text-align: left;
            min-height: 46px;
            padding: 11px 14px;
            border-radius: 12px;
            border: 1px solid rgba(28, 40, 52, 0.15);
            background: #ffffff;
            color: #14282d;
            font-size: 0.95rem;
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
            border-radius: 16px;
            padding: 20px;
            box-shadow: 0 16px 30px rgba(16, 58, 72, 0.06);
        }

        .settings-section+.settings-section {
            margin-top: 16px;
        }

        .settings-section h2 {
            margin-top: 0;
            margin-bottom: 10px;
            font-size: 1.15rem;
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
            gap: 12px;
            margin-top: 14px;
        }

        .settings-form label {
            display: block;
            font-weight: 600;
            margin-bottom: 6px;
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

        .settings-form input[type="checkbox"] {
            width: 18px;
            height: 18px;
            margin: 0 10px 0 0;
            vertical-align: middle;
            accent-color: #12747d;
        }

        .settings-form label:has(input[type="checkbox"]) {
            display: flex;
            align-items: center;
            min-height: 38px;
            margin-bottom: 0;
            padding: 8px 10px;
            border-radius: 10px;
            background: #f7fbfc;
        }

        .settings-form textarea {
            min-height: 96px;
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

        .settings-layout-compact .settings-section {
            padding: 16px;
        }

        .settings-layout-compact .settings-form {
            gap: 9px;
        }

        .settings-layout-compact .settings-form input,
        .settings-layout-compact .settings-form select,
        .settings-layout-compact .settings-form textarea {
            padding: 10px 12px;
        }

        .settings-layout-expanded .settings-section {
            padding: 28px;
        }

        .settings-layout-expanded .settings-form {
            gap: 18px;
        }

        @media (max-width: 920px) {
            .settings-grid {
                grid-template-columns: 1fr;
            }

            .settings-menu {
                position: static;
                grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            }
        }
    </style>
</head>

<body class="settings-layout-<?= htmlspecialchars($settings['layout']) ?>">
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
                                <option value="auto" <?= $settings['theme'] === 'auto' ? 'selected' : '' ?>>Auto (system)</option>
                                <option value="light" <?= $settings['theme'] === 'light' ? 'selected' : '' ?>>Light</option>
                                <option value="dark" <?= $settings['theme'] === 'dark' ? 'selected' : '' ?>>Dark</option>
                                <option value="green" <?= $settings['theme'] === 'green' ? 'selected' : '' ?>>Green farm theme</option>
                            </select>
                            <label for="layout">Dashboard layout</label>
                            <select id="layout" name="layout">
                                <option value="compact" <?= $settings['layout'] === 'compact' ? 'selected' : '' ?>>Compact</option>
                                <option value="standard" <?= $settings['layout'] === 'standard' ? 'selected' : '' ?>>Standard</option>
                                <option value="expanded" <?= $settings['layout'] === 'expanded' ? 'selected' : '' ?>>Expanded</option>
                            </select>
                            <div class="settings-actions">
                                <button type="button" class="btn-secondary" id="preview-theme">Preview theme</button>
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
                                    <option value="<?= htmlspecialchars($code) ?>" <?= $settings['language'] === $code ? 'selected' : '' ?>><?= htmlspecialchars($label) ?></option>
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
                        <form class="settings-form" method="post" action="settings.php">
                            <input type="hidden" name="section" value="notifications">
                            <label><input type="checkbox" name="notify_weather" value="1" <?= $settings['notify_weather'] === '1' ? 'checked' : '' ?>> Weather alerts</label>
                            <label><input type="checkbox" name="notify_market" value="1" <?= $settings['notify_market'] === '1' ? 'checked' : '' ?>> Market updates</label>
                            <label><input type="checkbox" name="notify_expert" value="1" <?= $settings['notify_expert'] === '1' ? 'checked' : '' ?>> Expert replies</label>
                            <label><input type="checkbox" name="notify_security" value="1" <?= $settings['notify_security'] === '1' ? 'checked' : '' ?>> Security notifications</label>
                            <div class="settings-actions">
                                <button type="submit" class="btn-primary">Save notifications</button>
                            </div>
                        </form>
                    </section>

                    <section id="security" class="settings-section">
                        <h2>Security</h2>
                        <p>Protect your account with password and two-factor settings.</p>
                        <form class="settings-form" method="post" action="settings.php">
                            <input type="hidden" name="section" value="security">
                            <label for="current-password">Current password</label>
                            <input id="current-password" name="current_password" type="password" placeholder="Current password">
                            <label for="new-password">New password</label>
                            <input id="new-password" name="new_password" type="password" placeholder="New password">
                            <label for="confirm-password">Confirm password</label>
                            <input id="confirm-password" name="confirm_password" type="password" placeholder="Confirm password">
                            <div class="settings-actions">
                                <button type="submit" class="btn-secondary" name="change_password" value="1">Change password</button>
                                <button type="submit" class="btn-secondary" name="enable_two_factor" value="1"><?= $settings['two_factor_enabled'] === '1' ? 'Disable 2FA preference' : 'Enable 2FA preference' ?></button>
                            </div>
                        </form>
                    </section>

                    <section id="farm-preferences" class="settings-section">
                        <h2>Farm Preferences</h2>
                        <p>Customize farm defaults such as crop alerts, land mode, and notification preferences.</p>
                        <form class="settings-form" method="post" action="settings.php">
                            <input type="hidden" name="section" value="farm_preferences">
                            <label for="land-mode">Land mode</label>
                            <select id="land-mode" name="land_mode">
                                <option value="own" <?= $settings['land_mode'] === 'own' ? 'selected' : '' ?>>Own farm</option>
                                <option value="virtual" <?= $settings['land_mode'] === 'virtual' ? 'selected' : '' ?>>Virtual planning</option>
                            </select>

                            <label for="crop-alerts">Crop alert level</label>
                            <select id="crop-alerts" name="crop_alerts">
                                <option value="normal" <?= $settings['crop_alerts'] === 'normal' ? 'selected' : '' ?>>Normal</option>
                                <option value="enhanced" <?= $settings['crop_alerts'] === 'enhanced' ? 'selected' : '' ?>>Enhanced</option>
                                <option value="critical" <?= $settings['crop_alerts'] === 'critical' ? 'selected' : '' ?>>Critical</option>
                            </select>

                            <label for="preferred-crops">Preferred crops</label>
                            <input id="preferred-crops" name="preferred_crops" type="text" value="<?= htmlspecialchars($settings['preferred_crops']) ?>" placeholder="e.g. Rice, Wheat, Vegetables">
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
                        <form class="settings-form" method="post" action="settings.php">
                            <input type="hidden" name="section" value="support">
                            <label for="support-message">Send a support request</label>
                            <textarea id="support-message" name="support_message" placeholder="Describe the issue or question..."></textarea>
                            <div class="settings-actions">
                                <button type="submit" class="btn-primary">Send request</button>
                            </div>
                        </form>
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
    <script>
        (function() {
            const savedTheme = <?= json_encode($settings['theme']) ?>;
            const savedLayout = <?= json_encode($settings['layout']) ?>;

            if (savedTheme === 'auto') {
                localStorage.removeItem('smartAgTheme');
            } else {
                localStorage.setItem('smartAgTheme', savedTheme);
            }
            localStorage.setItem('smartAgLayout', savedLayout);

            document.addEventListener('DOMContentLoaded', function() {
                const previewButton = document.getElementById('preview-theme');
                const themeSelect = document.getElementById('theme');
                const layoutSelect = document.getElementById('layout');

                function previewAppearance() {
                    const theme = themeSelect.value;
                    const layout = layoutSelect.value;
                    const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
                    const resolvedTheme = theme === 'auto' ? (prefersDark ? 'dark' : 'light') : theme;

                    document.body.classList.toggle('dark-mode', resolvedTheme === 'dark');
                    document.body.classList.toggle('green-mode', resolvedTheme === 'green');
                    document.body.classList.remove('settings-layout-compact', 'settings-layout-standard', 'settings-layout-expanded');
                    document.body.classList.add('settings-layout-' + layout);
                }

                if (previewButton && themeSelect && layoutSelect) {
                    previewButton.addEventListener('click', previewAppearance);
                }
            });
        })();
    </script>
</body>

</html>
