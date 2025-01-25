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

// รับข้อมูลจากการส่งคำขอแบบ POST
$data = json_decode(file_get_contents('php://input'), true);
$cart_id = $data['cart_id'];
$status = $data['status'];

// คำสั่ง SQL สำหรับอัปเดตสถานะของออเดอร์
$sql = "UPDATE cart_items SET status = ? WHERE id = ?";

// เตรียมคำสั่ง SQL
$stmt = $conn->prepare($sql);
if ($stmt === false) {
    die("เตรียมคำสั่ง SQL ล้มเหลว: " . $conn->error);
}

$stmt->bind_param("si", $status, $cart_id);
if ($stmt->execute()) {
    // หากสำเร็จ ส่งกลับข้อมูลสำเร็จ
    echo json_encode(["success" => true]);
} else {
    // หากล้มเหลว ส่งกลับข้อมูลข้อผิดพลาด
    echo json_encode(["success" => false, "message" => "ไม่สามารถอัปเดตสถานะได้"]);
}

$stmt->close();
$conn->close();
?>
