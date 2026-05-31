<?php
require_once __DIR__ . '/../../includes/session.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/db.php';
requireLogin();
$config = require __DIR__ . '/../../includes/config.php';

$error = '';
$success = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $topic = trim($_POST['topic'] ?? '');
    $details = trim($_POST['details'] ?? '');
    $contact = trim($_POST['contact'] ?? '');

    if (!$topic || !$details || !$contact) {
        $error = 'Please provide a topic, details, and contact information.';
    } else {
        $userId = $_SESSION['user_id'];
        $stmt = $mysqli->prepare('INSERT INTO consultations (user_id, topic, details, contact_info, status, requested_at) VALUES (?, ?, ?, ?, ?, NOW())');
        $status = 'Pending';
        $stmt->bind_param('issss', $userId, $topic, $details, $contact, $status);
        if ($stmt->execute()) {
            $success = 'Your consultation request has been submitted. An expert will contact you soon.';
            $_POST = [];
        } else {
            $error = 'Unable to send the request. Please try again later.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consultation - <?= htmlspecialchars($config['app_name']) ?></title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="stylesheet" href="../../assets/css/responsive.css">
</head>
<body>
    <?php include __DIR__ . '/../../includes/navbar.php'; ?>
    <main class="dashboard-page">
        <section class="dashboard-section">
            <h1>Expert Consultation</h1>
            <p>Submit your farming question and connect with an agriculture specialist.</p>
            <?php if ($success) : ?>
                <div class="alert-item alert-info"><?= htmlspecialchars($success) ?></div>
            <?php endif; ?>
            <?php if ($error) : ?>
                <div class="alert-item alert-high"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
            <form method="post" action="consultation.php" class="contact-form">
                <div class="form-group">
                    <label for="topic">Consultation topic</label>
                    <input type="text" id="topic" name="topic" required value="<?= htmlspecialchars($_POST['topic'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label for="details">Details</label>
                    <textarea id="details" name="details" rows="5" required><?= htmlspecialchars($_POST['details'] ?? '') ?></textarea>
                </div>
                <div class="form-group">
                    <label for="contact">Contact information</label>
                    <input type="text" id="contact" name="contact" required value="<?= htmlspecialchars($_POST['contact'] ?? '') ?>">
                </div>
                <div class="form-actions">
                    <button type="submit" class="btn-primary">Request Consultation</button>
                </div>
            </form>
        </section>
    </main>
</body>
</html>
