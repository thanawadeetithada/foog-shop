<?php
session_start();
require 'db_connection.php';

// ตรวจสอบข้อมูลที่ส่งมาจาก order_summary.php
$cart_id = $_POST['cart_id'] ?? null;
$total_price = $_POST['total_price'] ?? 0;

if ($cart_id && $cart_id === session_id()) {
    $cart = $_SESSION['cart'] ?? [];
    
    // บันทึกคำสั่งซื้อในฐานข้อมูล
    $order_query = "INSERT INTO orders (cart_id, total_price) VALUES (?, ?)";
    $stmt = $conn->prepare($order_query);
    $stmt->bind_param("si", $cart_id, $total_price);
    
    if ($stmt->execute()) {
        echo "<p>คำสั่งซื้อของคุณได้รับการยืนยันแล้ว!</p>";
        // เคลียร์ตะกร้าสินค้า
        unset($_SESSION['cart']);
    } else {
        echo "<p>เกิดข้อผิดพลาดในการยืนยันคำสั่งซื้อ กรุณาลองใหม่อีกครั้ง</p>";
    }
    
    $stmt->close();
} else {
    echo "<p>ข้อมูลคำสั่งซื้อไม่ถูกต้อง</p>";
}

$conn->close();
?>