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

$mysqli->query("CREATE TABLE IF NOT EXISTS user_settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    setting_key VARCHAR(80) NOT NULL,
    setting_value TEXT DEFAULT NULL,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY unique_user_setting (user_id, setting_key),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

$mysqli->query("CREATE TABLE IF NOT EXISTS support_requests (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    message TEXT NOT NULL,
    status VARCHAR(80) NOT NULL DEFAULT 'Open',
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

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

$mysqli->query("CREATE TABLE IF NOT EXISTS user_groups (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(200) NOT NULL,
    owner_user_id INT NOT NULL,
    owner_role ENUM('farmer','expert','market_member','assistant') NOT NULL DEFAULT 'farmer',
    description TEXT DEFAULT NULL,
    land_mode ENUM('own','virtual') NOT NULL DEFAULT 'own',
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (owner_user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

$mysqli->query("CREATE TABLE IF NOT EXISTS group_members (
    id INT AUTO_INCREMENT PRIMARY KEY,
    group_id INT NOT NULL,
    user_id INT NOT NULL,
    role ENUM('farmer','expert','market_member','assistant') NOT NULL DEFAULT 'farmer',
    joined_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (group_id) REFERENCES user_groups(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

$mysqli->query("CREATE TABLE IF NOT EXISTS group_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    group_id INT NOT NULL,
    user_id INT NOT NULL,
    title VARCHAR(200) NOT NULL,
    item_type ENUM('product','service') NOT NULL DEFAULT 'product',
    category VARCHAR(120) DEFAULT NULL,
    price DECIMAL(12,2) DEFAULT NULL,
    description TEXT DEFAULT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (group_id) REFERENCES user_groups(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
?>

