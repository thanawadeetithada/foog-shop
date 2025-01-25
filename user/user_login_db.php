<?php
session_start();
require 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $phone = $_POST['phone'];
    $password = $_POST['password'];

    // การเชื่อมต่อฐานข้อมูล
    $conn = new mysqli('localhost', 'root', '', 'store_management');

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT * FROM user WHERE phone = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $phone);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // ตรวจสอบรหัสผ่าน
        if (password_verify($password, $row['password'])) {
            // รหัสผ่านถูกต้อง ให้บันทึกข้อมูลเข้าสู่ระบบ
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['username'] = $row['username'];
            header("Location: manage_stores.php");
            exit();
        } else {
            $_SESSION['error_message'] = "รหัสผ่านไม่ถูกต้อง!";
            header("Location: user_login.php");
            exit();
        }
    } else {
        $_SESSION['error_message'] = "ไม่พบเบอร์โทรนี้!";
        header("Location: user_login.php");
        exit();
    }

    $stmt->close();
    $conn->close();
}
?>