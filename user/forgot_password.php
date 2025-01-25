<?php
session_start();
require 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $phone = $_POST['phone'];
    $new_password = password_hash($_POST['new_password'], PASSWORD_DEFAULT);

    // การเชื่อมต่อฐานข้อมูล
    $conn = new mysqli('localhost', 'root', '', 'store_management');

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // ตรวจสอบว่าหมายเลขโทรศัพท์มีอยู่ในระบบหรือไม่
    $check_phone_sql = "SELECT * FROM user WHERE phone = ?";
    $stmt = $conn->prepare($check_phone_sql);
    $stmt->bind_param("s", $phone);
    $stmt->execute();
    $check_phone_result = $stmt->get_result();

    if ($check_phone_result->num_rows > 0) {
        // อัปเดตรหัสผ่านใหม่
        $update_password_sql = "UPDATE user SET password = ? WHERE phone = ?";
        $stmt = $conn->prepare($update_password_sql);
        $stmt->bind_param("ss", $new_password, $phone);
        if ($stmt->execute()) {
            $_SESSION['success_message'] = "รหัสผ่านถูกอัปเดตเรียบร้อยแล้ว!";
            header("Location: user_login.php");
            exit();
        } else {
            echo "เกิดข้อผิดพลาด: " . $conn->error;
        }
    } else {
        $_SESSION['error_message'] = "ไม่พบเบอร์โทรนี้!";
        header("Location: forgot_password.php");
        exit();
    }

    $stmt->close();
    $conn->close(); // ปิดการเชื่อมต่อฐานข้อมูล
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ลืมรหัสผ่าน</title>
    <link rel="stylesheet" href="เปลี่ยนรหัส.css">
</head>
<body>
    <div class="forgot-password-container">
        <h2>ลืมรหัสผ่าน</h2>
        <?php
        if (isset($_SESSION['error_message'])) {
            echo '<p style="color:red;">' . $_SESSION['error_message'] . '</p>';
            unset($_SESSION['error_message']);
        }
        if (isset($_SESSION['success_message'])) {
            echo '<p style="color:green;">' . $_SESSION['success_message'] . '</p>';
            unset($_SESSION['success_message']);
        }
        ?>
        <form method="post">
            <input type="text" name="phone" placeholder="เบอร์โทร" required>
            <input type="password" name="new_password" placeholder="รหัสผ่านใหม่" required>
            <button type="submit">อัปเดตรหัสผ่าน</button>
        </form>
        <p>จำรหัสผ่านได้แล้ว? <a href="user_login.php">เข้าสู่ระบบ</a></p>
    </div>
</body>
</html>