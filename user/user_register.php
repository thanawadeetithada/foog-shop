<?php
// ตรวจสอบว่ามีการส่งข้อมูลจากฟอร์มหรือไม่
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['shop_name']) && isset($_POST['owner_name']) && isset($_POST['phone']) && isset($_POST['password'])) {

        // เชื่อมต่อฐานข้อมูล
        $servername = "localhost";  
        $username = "root";         
        $password = "";             
        $database = "store_management"; 

        $conn = new mysqli($servername, $username, $password, $database);

        // ตรวจสอบการเชื่อมต่อ
        if ($conn->connect_error) {
            die("การเชื่อมต่อล้มเหลว: " . $conn->connect_error);
        }

        // รับค่าจากฟอร์มและป้องกัน SQL Injection
        $shop_name = mysqli_real_escape_string($conn, $_POST['shop_name']);
        $owner_name = mysqli_real_escape_string($conn, $_POST['owner_name']);
        $phone = mysqli_real_escape_string($conn, $_POST['phone']);
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT);  // เข้ารหัสรหัสผ่าน

        // สร้างค่า store_id แบบสุ่ม (สามารถเปลี่ยนเป็นตรรกะอื่นได้)
        $store_id = rand(1000, 9999);

        // SQL เพื่อแทรกข้อมูล
        $sql = "INSERT INTO store_details (shop_name, owner_name, phone, password, store_id) 
                VALUES ('$shop_name', '$owner_name', '$phone', '$password', '$store_id')";

        if ($conn->query($sql) === TRUE) {
            echo "<script>alert('ลงทะเบียนสำเร็จ!'); window.location='user_login.php';</script>";
        } else {
            echo "ข้อผิดพลาด: " . $sql . "<br>" . $conn->error;
        }

        // ปิดการเชื่อมต่อฐานข้อมูล
        $conn->close();
    } else {
        echo "<script>alert('กรุณากรอกข้อมูลให้ครบถ้วน');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เข้าสู่ระบบผู้ใช้</title>
    <style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        color: black;
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

    h2 {
        color: #000;
        font-size: 2rem;
        margin-bottom: 1rem;
    }

    form input[type="text"],
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

    form input[type="text"]:focus,
    form input[type="password"]:focus {
        border-color: #f6a821;
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

    form button:hover {
        background-color: #f0f0f0;
    }

    .forgot-password {
        text-align: right;
        margin: 5px 0;
    }

    .forgot-password a {
        color: #000;
        text-decoration: none;
    }

    .forgot-password a:hover {
        text-decoration: underline;
    }

    .register-link a {
        color: #fff;
        text-decoration: none;
    }

    .register-link a:hover {
        text-decoration: underline;
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
    margin-top: 13rem;
    height: 100vh;
   
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
    </style>
</head>

<body>
    <div class="top-tab"></div>
    <div class="login-wrapper">
    <h2 class="login-title">ลงทะเบียน</h2>

        <div class="login-container">
            <form action="user_register.php" method="POST">
                <input type="text" name="shop_name" placeholder="ชื่อร้าน" required>
                <input type="text" name="owner_name" placeholder="ชื่อเจ้าของร้าน" required>
                <input type="text" name="phone" placeholder="เบอร์โทร" required>
                <input type="password" name="password" placeholder="รหัสผ่าน" required>
                <br><br>
                <button type="submit">ลงทะเบียน</button>
            </form>
            <br>
            <a href="user_login.php">เข้าสู่ระบบ</a>
        </div>
    </div>
</body>

</html>