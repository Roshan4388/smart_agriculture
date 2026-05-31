<?php
function generateOtpCode(int $length = 6): string {
    $code = '';
    for ($i = 0; $i < $length; $i++) {
        $code .= random_int(0, 9);
    }
    return $code;
}

function sendSmsMessage(string $to, string $message, array $config): bool {
    $provider = $config['sms']['provider'] ?? 'log';

    if ($provider === 'twilio') {
        $sid = $config['sms']['twilio']['sid'] ?? '';
        $token = $config['sms']['twilio']['token'] ?? '';
        $from = $config['sms']['twilio']['from'] ?? '';

        if (!$sid || !$token || !$from) {
            return false;
        }

        $postData = http_build_query([
            'From' => $from,
            'To' => $to,
            'Body' => $message,
        ]);

        $url = "https://api.twilio.com/2010-04-01/Accounts/{$sid}/Messages.json";
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERPWD, "{$sid}:{$token}");
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $result = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return $httpCode >= 200 && $httpCode < 300;
    }

    if ($provider === 'log') {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
        $_SESSION['otp_debug_message'] = $message;
        return true;
    }

    return false;
}

function sendOtpToPhone(mysqli $mysqli, int $userId, string $phone, array $config): bool {
    $length = intval($config['otp']['length'] ?? 6);
    $expiration = intval($config['otp']['expires_minutes'] ?? 10);
    $code = generateOtpCode($length);
    $expiresAt = date('Y-m-d H:i:s', strtotime("+{$expiration} minutes"));

    $stmt = $mysqli->prepare('INSERT INTO otp_codes (user_id, phone, code, expires_at) VALUES (?, ?, ?, ?)');
    if (!$stmt) {
        return false;
    }
    $stmt->bind_param('isss', $userId, $phone, $code, $expiresAt);
    if (!$stmt->execute()) {
        return false;
    }

    $message = "Your Smart Agriculture OTP code is: {$code}. It expires in {$expiration} minutes.";
    return sendSmsMessage($phone, $message, $config);
}

function verifyOtpCode(mysqli $mysqli, int $userId, string $code): bool {
    $stmt = $mysqli->prepare('SELECT id FROM otp_codes WHERE user_id = ? AND code = ? AND expires_at >= NOW() AND verified = 0 ORDER BY created_at DESC LIMIT 1');
    if (!$stmt) {
        return false;
    }
    $stmt->bind_param('is', $userId, $code);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    if (!$row) {
        return false;
    }

    $update = $mysqli->prepare('UPDATE otp_codes SET verified = 1 WHERE id = ?');
    if (!$update) {
        return false;
    }
    $update->bind_param('i', $row['id']);
    return $update->execute();
}
?>