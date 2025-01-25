<?php
session_start();
require 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = md5($_POST['password']); // เข้ารหัสรหัสผ่านด้วย MD5

    $query = "SELECT * FROM admin_users WHERE username = ? AND password = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $_SESSION['admin_username'] = $username;
        header("Location: addstore.php");
        exit();
    } else {
        $error = "ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง!";
    }
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เข้าสู่ระบบ</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #f3f4f6;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .login-form {
            background-color: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            padding: 20px 30px;
            width: 100%;
            max-width: 360px;
            text-align: center;
        }

        .login-form h2 {
            font-size: 24px;
            margin-bottom: 20px;
            color: #333;
        }

        .login-form input[type="text"],
        .login-form input[type="password"] {
            width: 80%;
            padding: 12px;
            margin-bottom: 16px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 16px;
        }

        .login-form button {
            width: 100%;
            padding: 12px;
            background-color: #4caf50;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
        }

        .login-form button:hover {
            background-color: #45a049;
        }

        .login-form .error {
            color: red;
            font-size: 14px;
            margin-bottom: 16px;
        }

        .login-form a {
            font-size: 14px;
            color: #007bff;
            text-decoration: none;
        }

        .login-form a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="login-form">
        <h2>เข้าสู่ระบบ</h2>
        <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
        <form method="post">
            <input type="text" name="username" placeholder="ชื่อผู้ใช้" required>
            <input type="password" name="password" placeholder="รหัสผ่าน" required>
            <button type="submit">เข้าสู่ระบบ</button>
        </form>
        <p><a href="#">ลืมรหัสผ่าน?</a></p>
    </div>
</body>
</html>