<?php
$host = "localhost";
$dbname = "expert_system";
$username = "root";
$password = "";
try {
    $conn = PDO::connect($host, $username, $password);
    $conn->setAttribute(
        PDO::ATTR_ERRMODE,
        PDO::ERRMODE_

    );
    $conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $conn->setAttribute(
        PDO::ATTR_DEFAULT_FETCH_MODE,
        PDO::FETCH_ASSOC
    );
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
