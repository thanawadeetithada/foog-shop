<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $phone = $_POST['phone'];
    $new_password = password_hash($_POST['new_password'], PASSWORD_DEFAULT);

    // เชื่อมต่อฐานข้อมูล
    $conn = new mysqli('localhost', 'root', '', 'store_management');
    if ($conn->connect_error) {
        die("การเชื่อมต่อฐานข้อมูลล้มเหลว: " . $conn->connect_error);
    }

    // ตรวจสอบข้อมูลยืนยันตัวตนเฉพาะเบอร์โทร
    $stmt = $conn->prepare("SELECT * FROM store_details WHERE phone = ?");
    $stmt->bind_param("s", $phone);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // อัปเดตรหัสผ่านใหม่
        $update_stmt = $conn->prepare("UPDATE store_details SET password = ? WHERE phone = ?");
        $update_stmt->bind_param("ss", $new_password, $phone);

        if ($update_stmt->execute()) {
            $_SESSION['success_message'] = "เปลี่ยนรหัสผ่านสำเร็จ! กรุณาเข้าสู่ระบบใหม่";
            header("Location: login.php");
            exit();
        } else {
            $_SESSION['error_message'] = "เกิดข้อผิดพลาดในการเปลี่ยนรหัสผ่าน";
            header("Location: forgot_password.php");
            exit();
        }
    } else {
        // ข้อมูลไม่ถูกต้อง
        $_SESSION['error_message'] = "ข้อมูลไม่ถูกต้อง กรุณาลองใหม่";
        header("Location: forgot_password.php");
        exit();
    }

    $stmt->close();
    $conn->close();
}
?>
