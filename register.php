<?php
session_start();
$config = require __DIR__ . '/includes/config.php';
$baseUrl = $config['base_url'];
require_once __DIR__ . '/includes/db.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $experience = trim($_POST['experience'] ?? '');
    $agricultureField = trim($_POST['agriculture_field'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $confirm = trim($_POST['confirm_password'] ?? '');

    if (!$name || !$email || !$phone || !$address || !$experience || !$agricultureField || !$password || !$confirm) {
        $error = 'Please fill in all fields.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Enter a valid email address.';
    } elseif (!preg_match('/^\+?[0-9 \-]{8,20}$/', $phone)) {
        $error = 'Enter a valid phone number with country code.';
    } elseif ($password !== $confirm) {
        $error = 'Passwords do not match.';
    } elseif (strlen($password) < 6) {
        $error = 'Password must be at least 6 characters.';
    } else {
        $stmt = $mysqli->prepare('SELECT id FROM users WHERE email = ? LIMIT 1');
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $error = 'This email is already registered.';
        } else {
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);
            $role = 'farmer';
            $stmt = $mysqli->prepare('INSERT INTO users (name, email, phone, address, experience, agriculture_field, password, role, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())');
            $stmt->bind_param('ssssssss', $name, $email, $phone, $address, $experience, $agricultureField, $passwordHash, $role);
            if ($stmt->execute()) {
                $_SESSION['user_id'] = $mysqli->insert_id;
                $_SESSION['user_name'] = $name;
                $_SESSION['user_role'] = $role;
                header('Location: dashboard.php');
                exit;
            }

            $error = 'Unable to create your account. Please try again later.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - <?= htmlspecialchars($config['app_name']) ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/responsive.css">
</head>

<body>
    <div class="container";>
        <form action="" method="post">
            <div class="auth-page">
    <div class="auth-card">
        <div class="auth-image">
            <img src="assets/images/auth-register.svg" alt="Register illustration">
        </div>
        <div class="auth-panel">
            <a href="sign-in.php" class="btn-back">&larr; Back to Sign In</a>
            <h1>Create Account</h1>
            <p>Register to access your farm dashboard, crop recommendations, and marketplace features.</p>
            <?php if ($error) : ?>
                <div id="signin-error" class="error-message"><?= htmlspecialchars($error) ?></div>
            <?php else: ?>
                <div id="signin-error" class="error-message" style="display:none;"></div>
            <?php endif; ?>
            <form method="post" action="register.php" onsubmit="return validateSignIn();">
                <div class="form-group">
                    <label for="name">Full Name</label>
                    <input type="text" id="name" name="name" placeholder="Your name" required value="<?= htmlspecialchars($_POST['name'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" placeholder="you@example.com" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label for="phone">Phone Number</label>
                    <input type="tel" id="phone" name="phone" placeholder="+977 9800000000" required value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label for="address">Address</label>
                    <input type="text" id="address" name="address" placeholder="Your address" required value="<?= htmlspecialchars($_POST['address'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label for="agriculture_field">Agriculture Field</label>
                    <input type="text" id="agriculture_field" name="agriculture_field" placeholder="E.g., horticulture, cereals, dairy" required value="<?= htmlspecialchars($_POST['agriculture_field'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label for="experience">Experience</label>
                    <input type="text" id="experience" name="experience" placeholder="E.g., 5 years of vegetable farming" required value="<?= htmlspecialchars($_POST['experience'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="Create a password" required>
                </div>
                <div class="form-group">
                    <label for="confirm_password">Confirm Password</label>
                    <input type="password" id="confirm_password" name="confirm_password" placeholder="Repeat your password" required>
                </div>
                <div class="form-group">
                    <label class="checkbox-label">
                        <input type="checkbox" name="terms" required> I agree to the <a href="#">Terms of Service</a> and <a href="#">Privacy Policy</a>.
                    </label>
                </div>
                <div class="form-actions">
                    <button type="submit" class="btn-primary">Register</button>
                    <a href="sign-in.php" class="btn-secondary">Back to Sign In</a>
                </div>
                <p class="auth-note">Already have an account? <a href="sign-in.php">Sign in</a></p>
            </form>
        </div>
    </div>
    <script src="assets/js/signin.js"></script>
</div>
        </form>
    </div>
</body>
</html>
