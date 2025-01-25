<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>สมัครสมาชิก</title>
    <link rel="stylesheet" href="user_register.css">
</head>
<body>
    <div class="container">
        <h1>สมัครสมาชิก</h1>
        <?php
        session_start();
        if (isset($_SESSION['error_message'])) {
            echo '<p style="color:red;">' . $_SESSION['error_message'] . '</p>';
            unset($_SESSION['error_message']);
        }
        ?>
        <form action="user_register_db.php" method="post">
            <input type="text" name="username" placeholder="ชื่อผู้ใช้" required>
            <input type="text" name="phone" placeholder="เบอร์โทร" required>
            <input type="password" name="password" placeholder="รหัสผ่าน" required>
            <button type="submit" class="register-btn">สมัครสมาชิก</button>
        </form>
        <a href="user_login.php" class="login-link">เข้าสู่ระบบ</a>
    </div>
</body>
</html>