<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เข้าสู่ระบบ</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="login-container">
        <h2>เข้าสู่ระบบ</h2>

        <?php
            session_start();
            if (isset($_SESSION['error_message'])) {
                echo "<p style='color:red;'>" . $_SESSION['error_message'] . "</p>"; //เบอร์โทรไม่ถูกต้อง
                unset($_SESSION['error_message']); // ลบข้อความหลังแสดง
            }
        ?>

        <form action="login_db.php" method="POST">
            <input type="text" name="phone" placeholder="เบอร์โทร" required>
            <input type="password" name="password" placeholder="รหัสผ่าน" required>
            <div class="forgot-password">
                <a href="forgot_password.php">ลืมรหัสผ่าน ?</a>
            </div>
            <button type="submit">เข้าสู่ระบบ</button>
        </form>
        <p>คุณมีแอคเคาท์แล้วหรือยัง? <a href="register.php">ลงทะเบียน</a></p>
    </div>
</body>
</html>
