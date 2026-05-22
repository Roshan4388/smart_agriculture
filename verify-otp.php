<?php
session_start();
$config = require __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/sms.php';

if (empty($_SESSION['otp_user_id'])) {
    header('Location: request-otp.php');
    exit;
}

$error = '';
$info = '';
$userId = intval($_SESSION['otp_user_id']);
$userName = $_SESSION['otp_user_name'] ?? 'User';
$phone = $_SESSION['otp_user_phone'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['resend'])) {
        $sent = sendOtpToPhone($mysqli, $userId, $phone, $config);
        if ($sent) {
            $info = 'A new OTP has been sent to your phone.';
        } else {
            $error = 'Unable to resend OTP. Please check your SMS settings.';
        }
    } else {
        $code = trim($_POST['otp_code'] ?? '');
        if (!$code) {
            $error = 'Enter the one-time code sent to your phone.';
        } elseif (!verifyOtpCode($mysqli, $userId, $code)) {
            $error = 'The code is incorrect or has expired. Please try again.';
        } else {
            $stmt = $mysqli->prepare('SELECT id, name, email, role FROM users WHERE id = ? LIMIT 1');
            $stmt->bind_param('i', $userId);
            $stmt->execute();
            $result = $stmt->get_result();
            $user = $result->fetch_assoc();

            if ($user) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['user_role'] = $user['role'];
                unset($_SESSION['otp_user_id'], $_SESSION['otp_user_name'], $_SESSION['otp_user_phone'], $_SESSION['otp_debug_message']);
                header('Location: dashboard.php');
                exit;
            }
            $error = 'Unable to log you in. Please try again.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify OTP - <?= htmlspecialchars($config['app_name']) ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/responsive.css">
</head>
<body class="auth-page">
    <div class="auth-card">
        <div class="auth-image">
            <img src="assets/images/auth-otp.svg" alt="OTP verification illustration">
        </div>
        <div class="auth-panel">
            <a href="request-otp.php" class="btn-back">&larr; Back to OTP Request</a>
            <h1>Verify OTP</h1>
            <p>Enter the one-time code sent to <strong><?= htmlspecialchars($phone) ?></strong>.</p>
            <?php if ($error) : ?>
                <div class="error-message"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
            <?php if ($info) : ?>
                <div class="info-message"><?= htmlspecialchars($info) ?></div>
            <?php endif; ?>
            <?php if (!empty($_SESSION['otp_debug_message'])) : ?>
                <div class="info-message">Debug OTP: <?= htmlspecialchars($_SESSION['otp_debug_message']) ?></div>
            <?php endif; ?>
            <form method="post" action="verify-otp.php" onsubmit="return validateSignIn();">
                <div class="form-group">
                    <label for="otp_code">OTP Code</label>
                    <input type="text" id="otp_code" name="otp_code" placeholder="Enter code" required>
                </div>
                <div class="form-actions">
                    <button type="submit" class="btn-primary">Verify Code</button>
                    <button type="submit" name="resend" value="1" class="btn-secondary">Resend OTP</button>
                </div>
            </form>
        </div>
    </div>
    <script src="assets/js/signin.js"></script>
</body>
</html>
