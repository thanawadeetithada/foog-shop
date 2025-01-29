
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ลืมรหัสผ่าน</title>
    <style>
    /* style.css */
body {
    font-family: Arial, sans-serif;
    background-color: #fff;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    margin: 0;
}

.container, .forgot-password-container, .login-container {
    width: 70%;
    max-width: 400px;
    background-color: #FFDE59;
    padding: 2rem;
    border-radius: 15px;
    box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
    text-align: center;
    transition: box-shadow 0.3s ease;
}

.container:hover, .forgot-password-container:hover, .login-container:hover {
    box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.3);
}

h1, h2 {
    color: #000;
    font-size: 2rem;
    margin-bottom: 1rem;
}

form {
    display: flex;
    flex-direction: column;
    align-items: center;
}

form input {
    width: 100%;
    padding: 0.75rem;
    margin: 0.5rem 0;
    border-radius: 25px;
    border: none;
    outline: none;
    font-size: 1rem;
    color: #333;
}

form input::placeholder {
    color: #aaa;
}

button, .register-btn {
    width: 100%;
    padding: 0.75rem;
    margin: 1rem 0;
    border-radius: 25px;
    border: 2px solid #000;
    background-color: #fff;
    color: #000;
    font-size: 1rem;
    cursor: pointer;
}

.login-link {
    display: block;
    color: black;
    text-decoration: none;
    font-size: 0.9rem;
    padding: 0.5rem 0;
    border-radius: 25px;
    transition: background-color 0.3s, color 0.3s;
}

.login-link.active {
    background-color: #fff;
    color: #000;
}
</style>
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