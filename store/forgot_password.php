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
            session_start();
            if (isset($_SESSION['error_message'])) {
                echo "<p style='color:red;'>" . $_SESSION['error_message'] . "</p>"; //รหัสผ่านไม่ถูกต้อง
                unset($_SESSION['error_message']);
            }
        ?>

        <form action="reset_password_db.php" method="POST">
            <input type="text" name="phone" placeholder="เบอร์โทร" required>
            <input type="password" name="new_password" placeholder="รหัสผ่านใหม่" required>
            <button type="submit">เปลี่ยนรหัสผ่าน</button>
        </form>

        <p>กลับไปหน้า <a href="login.php">เข้าสู่ระบบ</a></p>
    </div>
</body>
</html>
