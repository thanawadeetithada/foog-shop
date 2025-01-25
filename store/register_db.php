<?php
// เริ่ม session เพื่อใช้ส่งข้อความแจ้งเตือน
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $shop_name = $_POST['shop_name'];
    $owner_name = $_POST['owner_name'];
    $phone = $_POST['phone'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

        // การเชื่อมต่อฐานข้อมูล
    $conn = new mysqli('localhost', 'root', '', 'store_management');
    if ($conn->connect_error) {
        die("การเชื่อมต่อฐานข้อมูลล้มเหลว: " . $conn->connect_error);
    }

    // ตรวจสอบว่าหมายเลขโทรศัพท์มีอยู่ในระบบแล้วหรือไม่
    $check_phone_sql = "SELECT * FROM store_details WHERE phone = '$phone'";            //เพิ่มข้อมูลผู้ใช้ใหม่ลงในตาราง users หากเบอร์โทรนี้ยังไม่มีอยู่ในระบบ
    $check_phone_result = $conn->query($check_phone_sql);

    if ($check_phone_result->num_rows > 0) {
        // ตั้งค่า session แจ้งเตือนหากเบอร์โทรมีอยู่แล้ว
        $_SESSION['error_message'] = "เบอร์โทรนี้มีในระบบแล้ว กรุณาใช้เบอร์โทรอื่น";            //หากเบอร์โทรนี้มีอยู่แล้ว จะตั้งค่า $_SESSION['error_message'] ให้เป็น "เบอร์โทรนี้มีในระบบแล้ว กรุณาใช้เบอร์โทรอื่น" จากนั้นจะเปลี่ยนเส้นทางกลับไปที่ register.php
        header("Location: register.php");
        exit();
    } else {
        $sql = "INSERT INTO store_details (shop_name, owner_name, phone, password) VALUES ('$shop_name', '$owner_name', '$phone', '$password')";
        if ($conn->query($sql) === TRUE) {
            header("Location: login.php");        // หากเพิ่มข้อมูลสำเร็จ จะเปลี่ยนเส้นทางไปยังหน้า login.php
            exit();
        } else {
            echo "เกิดข้อผิดพลาด: " . $conn->error;
        }
    }

    $conn->close();    //ปิดการเชื่อมต่อฐานข้อมูล
}
?>
