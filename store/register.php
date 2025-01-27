<?php
// เริ่ม session เพื่อใช้ส่งข้อความแจ้งเตือน
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $shop_name = $_POST['shop_name'];
    $owner_name = $_POST['owner_name'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // การเชื่อมต่อฐานข้อมูล
    $conn = new mysqli('localhost', 'root', '', 'store_management');
    if ($conn->connect_error) {
        die("การเชื่อมต่อฐานข้อมูลล้มเหลว: " . $conn->connect_error);
    }

    // ตรวจสอบว่าหมายเลขโทรศัพท์มีอยู่ในระบบแล้วหรือไม่
    $check_phone_sql = "SELECT * FROM users WHERE phone = ?";
    $stmt = $conn->prepare($check_phone_sql);
    $stmt->bind_param("s", $phone);
    $stmt->execute();
    $check_phone_result = $stmt->get_result();

    if ($check_phone_result->num_rows > 0) {
        // ตั้งค่า session แจ้งเตือนหากเบอร์โทรมีอยู่แล้ว
        $_SESSION['error_message'] = "เบอร์โทรนี้มีในระบบแล้ว กรุณาใช้เบอร์โทรอื่น";
        header("Location: register.php");
        exit();
    } else {
        // เพิ่มผู้ใช้ใหม่
        $sql = "INSERT INTO users (username, password, phone) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $owner_name, $password, $phone);
        if ($stmt->execute()) {
            $user_id = $stmt->insert_id;

            // เพิ่มร้านค้าใหม่ที่เชื่อมโยงกับผู้ใช้
            $sql = "INSERT INTO stores (name, address, phone, user_id) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssi", $shop_name, $address, $phone, $user_id);
            if ($stmt->execute()) {
                $store_id = $stmt->insert_id;

                // เพิ่มข้อมูลใน store_details
                $sql = "INSERT INTO store_details (shop_name, owner_name, phone, password, store_id) VALUES (?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ssssi", $shop_name, $owner_name, $phone, $password, $store_id);
                $stmt->execute();

                header("Location: login.php"); // หากเพิ่มข้อมูลสำเร็จ จะเปลี่ยนเส้นทางไปยังหน้า login.php
                exit();
            } else {
                echo "เกิดข้อผิดพลาด: " . $conn->error;
            }
        } else {
            echo "เกิดข้อผิดพลาด: " . $conn->error;
        }
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
    <title>สมัครสมาชิกและเพิ่มร้านค้า</title>
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
    <h2 class="login-title">สมัครสมาชิกและเพิ่มร้านค้า</h2>

        <div class="login-container">
            <form action="user_register.php" method="POST">
                <input type="text" name="shop_name" placeholder="ชื่อร้าน" required>
                <input type="text" name="owner_name" placeholder="ชื่อเจ้าของร้าน" required>
                <input type="text" name="phone" placeholder="เบอร์โทร" required>
                <input type="text" name="adress" placeholder="ที่อยู่" required>
                <input type="password" name="password" placeholder="รหัสผ่าน" required>
                <br><br>
                <button type="submit">สมัครสมาชิกและเพิ่มร้านค้า</button>
            </form>
            <br>
        </div>
    </div>
</body>

</html>