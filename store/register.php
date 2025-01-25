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
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h2>สมัครสมาชิกและเพิ่มร้านค้า</h2>
    <?php
    if (isset($_SESSION['error_message'])) {
        echo '<p style="color:red;">' . $_SESSION['error_message'] . '</p>';
        unset($_SESSION['error_message']);
    }
    ?>
    <form method="post">
        <input type="text" name="shop_name" placeholder="ชื่อร้าน" required>
        <input type="text" name="owner_name" placeholder="ชื่อเจ้าของร้าน" required>
        <input type="text" name="phone" placeholder="เบอร์โทร" required>
        <input type="text" name="address" placeholder="ที่อยู่" required>
        <input type="password" name="password" placeholder="รหัสผ่าน" required>
        <button type="submit">สมัครสมาชิกและเพิ่มร้านค้า</button>
    </form>
</body>
</html>