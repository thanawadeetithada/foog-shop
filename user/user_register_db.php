<?php
session_start();
require 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $phone = $_POST['phone'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // การเชื่อมต่อฐานข้อมูล
    $conn = new mysqli('localhost', 'root', '', 'store_management');

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // ตรวจสอบว่าหมายเลขโทรศัพท์มีอยู่ในระบบแล้วหรือไม่
    $check_phone_sql = "SELECT * FROM user WHERE phone = ?";
    $stmt = $conn->prepare($check_phone_sql);
    $stmt->bind_param("s", $phone);
    $stmt->execute();
    $check_phone_result = $stmt->get_result();

    if ($check_phone_result->num_rows > 0) {
        // ตั้งค่า session แจ้งเตือนหากเบอร์โทรมีอยู่แล้ว
        $_SESSION['error_message'] = "เบอร์โทรนี้มีในระบบแล้ว กรุณาใช้เบอร์โทรอื่น";
        header("Location: register.php");
        exit();
    } else {
        // เพิ่มผู้ใช้ใหม่
        $sql = "INSERT INTO user (username, password, phone) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $username, $password, $phone);
        if ($stmt->execute()) {
            header("Location: user_login.php"); // หากเพิ่มข้อมูลสำเร็จ จะเปลี่ยนเส้นทางไปยังหน้า login.php
            exit();
        } else {
            echo "เกิดข้อผิดพลาด: " . $conn->error;
        }
    }

    $stmt->close();
    $conn->close(); // ปิดการเชื่อมต่อฐานข้อมูล
}
?>