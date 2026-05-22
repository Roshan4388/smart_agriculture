<?php
session_start();
$config = require __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/sms.php';

$error = '';
$info = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');

    if (!$email) {
        $error = 'Please enter your registered email address.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Enter a valid email address.';
    } else {
        $stmt = $mysqli->prepare('SELECT id, name, phone, role FROM users WHERE email = ? LIMIT 1');
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if (!$user) {
            $error = 'No account was found with that email address.';
        } elseif (empty($user['phone'])) {
            $error = 'Your account does not have a phone number saved. Please update your profile or register again.';
        } else {
            $sent = sendOtpToPhone($mysqli, intval($user['id']), $user['phone'], $config);
            if ($sent) {
                $_SESSION['otp_user_id'] = intval($user['id']);
                $_SESSION['otp_user_name'] = $user['name'];
                $_SESSION['otp_user_phone'] = $user['phone'];
                header('Location: verify-otp.php');
                exit;
            }
            $error = 'Unable to send OTP. Check SMS settings and try again.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Request OTP - <?= htmlspecialchars($config['app_name']) ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/responsive.css">
</head>
<body class="auth-page">
    <div class="auth-card">
        <div class="auth-image">
            <img src="assets/images/auth-otp.svg" alt="OTP request illustration">
        </div>
        <div class="auth-panel">
            <a href="sign-in.php" class="btn-back">&larr; Back to Sign In</a>
            <h1>Request OTP</h1>
            <p>Enter your registered email and we'll send a one-time code to your phone.</p>
            <?php if ($error) : ?>
                <div class="error-message"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
            <?php if ($info) : ?>
                <div class="info-message"><?= htmlspecialchars($info) ?></div>
            <?php endif; ?>
            <?php if (!empty($_SESSION['otp_debug_message'])) : ?>
                <div class="info-message">Debug OTP: <?= htmlspecialchars($_SESSION['otp_debug_message']) ?></div>
            <?php endif; ?>
            <form method="post" action="request-otp.php" onsubmit="return validateSignIn();">
                <div class="form-group">
                    <label for="email">Registered Email</label>
                    <input type="email" id="email" name="email" placeholder="you@example.com" required>
                </div>
                <div class="form-actions">
                    <button type="submit" class="btn-primary">Send OTP</button>
                    <a href="sign-in.php" class="btn-secondary">Back to Sign In</a>
                </div>
            </form>
        </div>
    </div>
    <script src="assets/js/signin.js"></script>
</body>
</html>
