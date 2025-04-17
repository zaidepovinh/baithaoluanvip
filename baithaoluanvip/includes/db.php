<?php
// Cấu hình kết nối MySQL
$mysql_host = "localhost:3366"; 
$mysql_username = "root"; // Thay đổi username theo cấu hình của bạn
$mysql_password = ""; // Thay đổi password theo cấu hình của bạn
$mysql_db = "toobeauty";

// Kết nối sử dụng PDO
try {
    $pdo = new PDO("mysql:host=$mysql_host;dbname=$mysql_db;charset=utf8mb4", $mysql_username, $mysql_password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database Connection Failed: " . $e->getMessage());
}

// Kết nối mysqli (tùy chọn)
$conn = new mysqli($mysql_host, $mysql_username, $mysql_password, $mysql_db);
if ($conn->connect_error) {
    die("MySQLi Connection failed: " . $conn->connect_error);
}
$conn->set_charset("utf8mb4");
?>