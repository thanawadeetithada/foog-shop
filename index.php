<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เข้าสู่ระบบ</title>
    <style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        color: blue;
    }

    body {
        font-family: 'Sarabun', sans-serif !important;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        background-color: #fff;
    }

    .login-container {
        background-color: #FDDF59;
        padding: 2rem;
        width: 90%;
        max-width: 400px;
        border-radius: 15px;
        text-align: center;
        box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
        transition: box-shadow 0.3s ease;
    }

    .login-container:hover {
        box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.3);
    }

    .top-tab {
        width: 100%;
        padding: 30px;
        background-color: #FDDF59;
        position: fixed;
        top: 0;
        left: 0;
        z-index: 1000;
    }

    h2 {
        color: #000;
        font-size: 2rem;
        margin-bottom: 1rem;
    }

    form button {
        width: 100%;
        padding: 0.75rem;
        background-color: #fff;
        color: #000;
        border: 2px solid #000;
        border-radius: 25px;
        font-size: 1rem;
        cursor: pointer;
        margin-top: 1rem;
        transition: background-color 0.3s ease;
    }

    .forgot-password {
        text-align: right;
        margin: 5px 0;
    }

    .forgot-password a {
        color: #000;
        text-decoration: none;
    }

    p {
        margin-top: 15px;
        font-size: 0.9rem;
        color: #000;
    }

    .login-title {
        color: #000;
        font-size: 2rem;
        margin-bottom: 2rem;
        text-align: left;
        width: 100%;
        padding-left: 20px;
    }

    .login-wrapper {
        display: flex;
        flex-direction: column;
        align-items: center;
        padding-top: 7rem;
        height: 100vh;
    }

    form input[type="tel"],
    form input[type="password"] {
        width: 100%;
        padding: 0.75rem;
        margin: 0.5rem 0;
        border-radius: 25px;
        border: 1px solid #ccc;
        outline: none;
        font-size: 1rem;
        color: #333;
        transition: border 0.3s ease;
    }

    form input[type="tel"]:focus,
    form input[type="password"]:focus {
        border-color: #f6a821;
    }
    </style>
</head>

<body>
    <div class="top-tab"></div>
    <div class="login-wrapper">
        <h2 class="login-title">เข้าสู่ระบบ</h2>
        <div class="login-container">
            <form action="login_db.php" method="POST">
                <input type="tel" name="phone" placeholder="เบอร์โทร" required pattern="[0-9]{10}" maxlength="10"
                    oninput="this.value = this.value.replace(/[^0-9]/g, ''); this.setCustomValidity('');"
                    oninvalid="this.setCustomValidity('กรุณาใส่เบอร์โทรให้ถูกต้อง (ตัวเลข 10 หลัก)');">

                <input type="password" name="password" placeholder="รหัสผ่าน" required>
                <div class="forgot-password">
                    <a href="forgot_password.php">ลืมรหัสผ่าน ?</a>
                </div>
                <button type="submit">เข้าสู่ระบบ</button>
            </form>
            <p>คุณมีแอคเคาท์แล้วหรือยัง? <br>
            <br>
            <a href="shop_register.php">ลงทะเบียนเป็นร้านค้า</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <a href="register.php">ลงทะเบียนเป็นลูกค้า</a></p>
        </div>
    </div>
</body>

</html>