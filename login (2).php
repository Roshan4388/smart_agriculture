<?php
session_start();
require_once __DIR__ . '/includes/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: sign-in.php');
    exit;
}

$email = trim($_POST['email'] ?? '');
$password = trim($_POST['password'] ?? '');
$error = 'Invalid login credentials.';

if (!$email || !$password) {
    header('Location: sign-in.php?error=' . urlencode('Enter email and password.'));
    exit;
}

$stmt = $mysqli->prepare('SELECT id, name, email, password, role FROM users WHERE email = ? LIMIT 1');
$stmt->bind_param('s', $email);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($user && password_verify($password, $user['password'])) {
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['user_name'] = $user['name'];
    $_SESSION['user_role'] = $user['role'];
    header('Location: dashboard.php');
    exit;
}

header('Location: sign-in.php?error=' . urlencode($error));
exit;
