<?php
$servername = "sql212.ezyro.com";  // ตรวจสอบว่าตรงกับที่ ProFreeHost ให้มา
$username = "ezyro_38183430";      // Username ที่ ProFreeHost ให้
$password = "fddeb93b9abe9d0";      // Password ของคุณ
$dbname = "ezyro_38183430_food_shop";  // ชื่อฐานข้อมูล

// สร้างการเชื่อมต่อ MySQL
$conn = new mysqli($servername, $username, $password, $dbname);

// ตรวจสอบข้อผิดพลาด
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// ตั้งค่าการเข้ารหัสเป็น UTF-8
$conn->set_charset("utf8mb4");
?>
