<?php
session_start();
include 'db.php'; // ไฟล์เชื่อมต่อฐานข้อมูล

if (!isset($_SESSION['user_id']) || (!in_array($_SESSION['role'], ['admin'])) ) {
    header('Location: index.php'); 
    exit();
}

// ตรวจสอบว่ามีค่าที่ส่งมาหรือไม่
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['store_id'])) {
    $store_id = intval($_POST['store_id']); // แปลงให้เป็นตัวเลขเพื่อความปลอดภัย

    // ตรวจสอบว่ามีร้านค้านี้ในฐานข้อมูลหรือไม่
    $check_sql = "SELECT * FROM stores WHERE store_id = ?";
    $stmt = $conn->prepare($check_sql);
    $stmt->bind_param("i", $store_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        echo "<script>alert('ไม่พบร้านค้านี้ในระบบ!'); window.location.href='admin_add_shop.php';</script>";
        exit();
    }

    // ลบข้อมูลร้านค้า
    $delete_sql = "DELETE FROM stores WHERE store_id = ?";
    $stmt = $conn->prepare($delete_sql);
    $stmt->bind_param("i", $store_id);

    if ($stmt->execute()) {
        echo "<script>alert('ลบร้านค้าเรียบร้อยแล้ว!'); window.location.href='admin_add_shop.php';</script>";
    } else {
        echo "<script>alert('เกิดข้อผิดพลาดในการลบร้านค้า!'); window.location.href='admin_add_shop.php';</script>";
    }

    $stmt->close();
    $conn->close();
} else {
    echo "<script>alert('คำขอลบข้อมูลไม่ถูกต้อง!'); window.location.href='admin_add_shop.php';</script>";
    exit();
}
?>
