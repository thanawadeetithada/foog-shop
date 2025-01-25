<?php
session_start();
require 'db_connection.php';

// ตรวจสอบว่ามีการส่ง product_id และ store_id มาหรือไม่
$product_id = $_POST['product_id'] ?? null;
$store_id = $_POST['store_id'] ?? null;

if ($product_id && $store_id) {
    // ดึงข้อมูลผลิตภัณฑ์จากฐานข้อมูล
    $product_query = "SELECT * FROM products WHERE id = ?";
    $stmt = $conn->prepare($product_query);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $product_result = $stmt->get_result();
    $product = $product_result->fetch_assoc();
    $stmt->close();

    // ตรวจสอบว่าผลิตภัณฑ์มีอยู่และสามารถซื้อได้
    if ($product && $product['is_available']) {
        // เพิ่มผลิตภัณฑ์ลงในตะกร้า
        $cart_item = [
            'id' => $product['id'],
            'name' => $product['name'],
            'price' => $product['price']
        ];

        $_SESSION['cart'][] = $cart_item;

        // เปลี่ยนเส้นทางไปยังหน้าตะกร้า
        header("Location: cart.php");
        exit;
    } else {
        echo "<p>ผลิตภัณฑ์ไม่สามารถซื้อได้</p>";
    }
} else {
    echo "<p>ข้อมูลผลิตภัณฑ์ไม่ถูกต้อง</p>";
}
?>