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
        text-decoration: none;
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
        display: flex;
        align-items: center;
        align-content: space-between;
        justify-content: center;
        padding: 2rem;
        width: 90%;
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
        border: 1px solid black;
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
        background-color: #ffde59;
        color: #000;
        border: 0px;
        border-radius: 25px;
        font-size: 1rem;
        cursor: pointer;
        margin-top: 1rem;
        transition: background-color 0.3s ease;
    }

    .cancel {
        width: 100%;
        padding: 0.75rem;
        background-color: #ffde59;
        color: #000;
        border: 0px;
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

    .import-img {
        width: 100%;
        padding: 0.6rem;
        margin: 0.5rem 0;
        border-radius: 25px;
        border: 1px solid black;
        outline: none;
        font-size: 1rem;
        color: #333;
        transition: border 0.3s ease;
    }

    .header {
        margin-top: 2rem;
        color: #333;
        padding: 0px;
        font-size: 1.5em;
    }

    .option-row {
        display: flex;
        justify-content: space-between;
        gap: 10px;
        margin-bottom: 10px;
    }

    .option-row .option {
        width: 70%;
    }

    .option-row .extra {
        width: 30%
    }
    </style>
</head>

<body>
    <div class="login-wrapper">
    <div class="header">เพิ่มสินค้าใหม่</div>
        <div class="login-container">
            <form action="user_register.php" method="POST">
                <span>ชื่อสินค้า : </span>
                <input type="text" name="shop_name" required>
                <span>ราคา (บาท) : </span>
                <input type="text" name="owner_name"  required>
                <div class="option-row">
                    <span>ตัวเลือกเพิ่มเติม :</span>
                    <span>ค่าใช้จ่ายเพิ่มเติม :</span>
                </div>
                <div class="option-row">
                    <input class="option" type="text" name="option1" placeholder="" required>
                    <input class="extra" type="text" name="extra2" placeholder="" required>
                </div>
                <div class="option-row">
                    <input class="option" type="text" name="option2" placeholder="" required>
                    <input class="extra" type="text" name="extra2" placeholder="" required>
                </div>

                <span>รูปภาพสินค้า : </span>
                <div class="import-img">
                    <input type="file" name="payment_proof" id="payment_proof" required>
                </div>
                <span>หมายเหตุ : </span>
                <input type="text" name="owner_name" placeholder="" required>
                <br>
                <button type="submit">บันทึกสินค้า</button>
                <button class="cancel" type="submit">กลับไปหน้าหลัก</button>
            </form>
            <br>
        </div>
    </div>
</body>

</html>