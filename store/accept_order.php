<?php
// ตั้งค่าการเชื่อมต่อฐานข้อมูล
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "store_management";

// สร้างการเชื่อมต่อ
$conn = new mysqli($servername, $username, $password, $dbname);

// ตรวจสอบการเชื่อมต่อ
if ($conn->connect_error) {
    die("การเชื่อมต่อล้มเหลว: " . $conn->connect_error);
}

// รับข้อมูลจากคำขอ JSON
$data = json_decode(file_get_contents('php://input'), true);
$cart_id = $data['cart_id'];

// ตรวจสอบว่าได้รับ cart_id หรือไม่
if (isset($cart_id)) {
    // คำสั่ง SQL สำหรับอัพเดตสถานะเป็น "pending"
    $sql = "UPDATE cart_items SET status = 'pending' WHERE id = ?";

    // เตรียมคำสั่ง SQL
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $cart_id);
        if ($stmt->execute()) {
            // ถ้าการอัพเดตสำเร็จ
            echo json_encode(["success" => true]);
        } else {
            // ถ้ามีข้อผิดพลาดในการอัพเดต
            echo json_encode(["success" => false, "message" => "ไม่สามารถอัพเดตสถานะได้"]);
        }
        $stmt->close();
    } else {
        echo json_encode(["success" => false, "message" => "ไม่สามารถเตรียมคำสั่ง SQL ได้"]);
    }
} else {
    echo json_encode(["success" => false, "message" => "ไม่มี cart_id"]);
}

// ปิดการเชื่อมต่อ
$conn->close();
?>
