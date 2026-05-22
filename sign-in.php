<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit;
}
$config = require __DIR__ . '/includes/config.php';
$baseUrl = $config['base_url'];
$error = isset($_GET['error']) ? htmlspecialchars($_GET['error']) : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In - <?= htmlspecialchars($config['app_name']) ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/responsive.css">
</head>
<body class="auth-page">
    <div class="auth-card">
        <h1>Sign In</h1>
        <p>Securely access your Smart Agriculture dashboard and field intelligence.</p>
        <?php if ($error) : ?>
            <div class="error-message"><?= $error ?></div>
        <?php endif; ?>
        <form id="signin-form" onsubmit="return validateSignIn();" method="post" action="login.php">
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="you@example.com" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Enter your password" required>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn-primary">Sign In</button>
            </div>
            <p class="auth-note">Don't have an account? <a href="register.php">Register now</a></p>
        </form>
    </div>
    <script src="assets/js/signin.js"></script>
</body>
</html>
