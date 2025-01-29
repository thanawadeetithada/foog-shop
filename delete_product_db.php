<?php
session_start();
include 'db.php';  // เชื่อมต่อฐานข้อมูล

// ตรวจสอบว่า login แล้วหรือยัง และ user เป็น store_owner
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'store_owner' || !isset($_SESSION['store_id'])) {
    header('Location: index.php');  // ถ้าไม่ได้ล็อกอินหรือไม่ใช่เจ้าของร้าน ให้กลับไปที่หน้า login
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['product_id'])) {
    $product_id = $_POST['product_id'];  // รับค่า product_id จากฟอร์ม

    // ตรวจสอบว่าเป็นสินค้าของ store_owner นี้หรือไม่
    $store_id = $_SESSION['store_id'];

    // ลบสินค้าในฐานข้อมูล
    $sql = "DELETE FROM products WHERE product_id = ? AND store_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $product_id, $store_id);  // ผูกค่า product_id และ store_id
    if ($stmt->execute()) {
        // ถ้าลบสำเร็จ
        echo "<script>alert('ลบสินค้าเรียบร้อย'); window.location.href = 'shop_all_product.php';</script>";
    } else {
        // ถ้ามีข้อผิดพลาดในการลบ
        echo "<script>alert('ไม่สามารถลบสินค้าได้'); window.location.href = 'shop_all_product.php';</script>";
    }

    $stmt->close();
} else {
    // ถ้าไม่มีการส่งค่า product_id
    echo "<script>alert('ไม่พบสินค้า'); window.location.href = 'shop_all_product.php';</script>";
}

$conn->close();
?>
