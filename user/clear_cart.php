<?php
session_start();
require 'db_connection.php';

if (!$connection) {
    die("Failed to connect to database.");
}

$user_id = 1; // ต้องดึง user_id จากผู้ใช้ที่ล็อกอิน
$query = "DELETE FROM cart_items WHERE user_id = ?";
$stmt = $connection->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();

// ล้างข้อมูลในเซสชัน
unset($_SESSION['cart']);

// ย้อนกลับไปหน้าตะกร้าสินค้า
header("Location: cart.php");
exit;
?>
