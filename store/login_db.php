<?php
session_start(); //เริ่มต้นการใช้งาน session ซึ่งจะใช้สำหรับจัดเก็บข้อมูลการเข้าสู่ระบบหรือข้อความผิดพลาดที่จะใช้แสดงผลหลังการตรวจสอบ

if ($_SERVER['REQUEST_METHOD'] == 'POST') { //ตรวจสอบว่ามีการส่งข้อมูลผ่านฟอร์มด้วยวิธี POST เพื่อให้โค้ดนี้ทำงานเฉพาะเมื่อฟอร์มถูกส่ง (ป้องกันไม่ให้รันเมื่อผู้ใช้เพียงแค่เข้ามาที่หน้าโดยตรง)
    $phone = $_POST['phone'];
    $password = $_POST['password']; // จะเก็บข้อมูลเบอร์โทรและรหัสผ่านที่ผู้ใช้กรอก

    // การเชื่อมต่อฐานข้อมูล
    $conn = new mysqli('localhost', 'root', '', 'store_management');

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);  // จะตรวจสอบว่าการเชื่อมต่อสำเร็จหรือไม่ หากเกิดข้อผิดพลาดจะหยุดการทำงานและแสดงข้อความ "Connection failed"
    }

    $sql = "SELECT * FROM store_details WHERE phone = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $phone);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {   // จะตรวจสอบว่าพบข้อมูลผู้ใช้หรือไม่
        $row = $result->fetch_assoc();

        // ตรวจสอบรหัสผ่าน
        if (password_verify($password, $row['password'])) {   // จะตรวจสอบว่ารหัสผ่านที่ผู้ใช้กรอกเข้ามาตรงกับรหัสผ่านในฐานข้อมูลหรือไม่ โดยใช้ password_verify สำหรับการตรวจสอบรหัสผ่านที่ถูกแฮช
            // รหัสผ่านถูกต้อง ให้บันทึกข้อมูลเข้าสู่ระบบ
            $_SESSION['user_id'] = $row['id']; // เก็บ ID ของผู้ใช้ใน session
            $_SESSION['username'] = $row['owner_name']; // เก็บชื่อผู้ใช้ใน session
            header("Location: Order 20Status.php"); // เปลี่ยนเส้นทางไปยังหน้าแสดงสินค้า
            exit(); // หยุดการทำงานของโค้ดหลังจาก header()
        } else {
            $_SESSION['error_message'] = "รหัสผ่านไม่ถูกต้อง!";  // หากรหัสผ่านผิด
            header("Location: login.php"); // กลับไปที่หน้า login.php
            exit();                                                 
        }
    } else {
        $_SESSION['error_message'] = "ไม่พบเบอร์โทรนี้!";      // หากไม่พบเบอร์โทรในฐานข้อมูล
        header("Location: login.php"); // กลับไปที่หน้า login.php
        exit();
    }

    $stmt->close();
    $conn->close();   // ปิดการเชื่อมต่อฐานข้อมูล
}
?>