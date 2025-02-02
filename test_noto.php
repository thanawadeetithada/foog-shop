<?php
include 'db.php'; 

// กำหนดคำสั่ง SQL ที่จะอัพเดท
$sql = "UPDATE orders_status SET notification = 1"; // หรือเพิ่มเงื่อนไข WHERE ตามต้องการ

// ตรวจสอบว่าอัพเดทสำเร็จหรือไม่
if ($conn->query($sql) === TRUE) {
    echo "Record updated successfully";
} else {
    echo "Error updating record: " . $conn->error;
}

// ปิดการเชื่อมต่อ
$conn->close();
?>
