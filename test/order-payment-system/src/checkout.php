<?php
session_start();
require 'db_connection.php';

// ตรวจสอบว่ามีการส่งข้อมูลคำสั่งซื้อมาหรือไม่
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cart_id = $_POST['cart_id'] ?? null;
    $total_price = $_POST['total_price'] ?? 0;

    if ($cart_id && $cart_id === session_id()) {
        $cart = $_SESSION['cart'] ?? [];
        
        // ตรวจสอบว่ามีสินค้าในตะกร้าหรือไม่
        if (!empty($cart)) {
            // บันทึกคำสั่งซื้อในฐานข้อมูล
            $order_query = "INSERT INTO orders (cart_id, total_price) VALUES (?, ?)";
            $stmt = $conn->prepare($order_query);
            $stmt->bind_param("sd", $cart_id, $total_price);
            $stmt->execute();
            $stmt->close();

            // เคลียร์ตะกร้าสินค้า
            unset($_SESSION['cart']);
            echo "<p>คำสั่งซื้อของคุณได้รับการยืนยันแล้ว!</p>";
            echo "<p>ยอดรวม: $total_price ฿</p>";
        } else {
            echo "<p>ไม่มีสินค้าในคำสั่งซื้อ</p>";
        }
    } else {
        echo "<p>ข้อมูลคำสั่งซื้อไม่ถูกต้อง</p>";
    }
} else {
    echo "<p>กรุณาทำการชำระเงินผ่านแบบฟอร์ม</p>";
}
?>