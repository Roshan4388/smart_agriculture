<?php
$host = '127.0.0.1';
$port = 3306;
$user = 'root';
$password = '';
$database = 'smart_agriculture';

$mysqli = new mysqli($host, $user, $password, $database, $port);
if ($mysqli->connect_error) {
    die('Connection failed: ' . $mysqli->connect_error);
}

$phoneColumn = $mysqli->query("SHOW COLUMNS FROM users LIKE 'phone'");
if ($phoneColumn && $phoneColumn->num_rows === 0) {
    $mysqli->query("ALTER TABLE users ADD COLUMN phone VARCHAR(20) DEFAULT NULL");
}

$addressColumn = $mysqli->query("SHOW COLUMNS FROM users LIKE 'address'");
if ($addressColumn && $addressColumn->num_rows === 0) {
    $mysqli->query("ALTER TABLE users ADD COLUMN address VARCHAR(255) DEFAULT NULL");
}

$experienceColumn = $mysqli->query("SHOW COLUMNS FROM users LIKE 'experience'");
if ($experienceColumn && $experienceColumn->num_rows === 0) {
    $mysqli->query("ALTER TABLE users ADD COLUMN experience VARCHAR(100) DEFAULT NULL");
}

$agricultureFieldColumn = $mysqli->query("SHOW COLUMNS FROM users LIKE 'agriculture_field'");
if ($agricultureFieldColumn && $agricultureFieldColumn->num_rows === 0) {
    $mysqli->query("ALTER TABLE users ADD COLUMN agriculture_field VARCHAR(100) DEFAULT NULL");
}

$mysqli->query("CREATE TABLE IF NOT EXISTS otp_codes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    phone VARCHAR(30) NOT NULL,
    code VARCHAR(10) NOT NULL,
    expires_at DATETIME NOT NULL,
    verified TINYINT(1) NOT NULL DEFAULT 0,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX(user_id),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
?>

