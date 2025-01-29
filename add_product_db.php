<?php
session_start();
require 'db.php'; // เชื่อมต่อฐานข้อมูล

// ตรวจสอบว่าผู้ใช้ล็อกอินหรือไม่
if (!isset($_SESSION['user_id'])) {
    die("กรุณาเข้าสู่ระบบก่อนเพิ่มสินค้า");
}

$user_id = $_SESSION['user_id'];

// ดึง store_id ของเจ้าของร้าน
$query = $conn->prepare("SELECT store_id FROM stores WHERE owner_id = ?");
$query->bind_param("i", $user_id);
$query->execute();
$query->bind_result($store_id);
$query->fetch();
$query->close();

if (!$store_id) {
    die("ไม่พบร้านค้าที่เกี่ยวข้องกับบัญชีนี้");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // รับค่าจากฟอร์ม
    $product_name = $_POST['product_name'];
    $price = $_POST['price'];
    $notes = $_POST['notes'] ?? '';

    // แปลงตัวเลือกเพิ่มเติมและค่าใช้จ่ายเป็น JSON
    $options = isset($_POST['option']) ? json_encode($_POST['option']) : json_encode([]);
    $extra_costs = isset($_POST['extra_cost']) ? json_encode($_POST['extra_cost']) : json_encode([]);

    // จัดการอัปโหลดรูปภาพ
    $target_dir = "uploads/";
    $image_url = NULL;

    if (!empty($_FILES["product_image"]["name"])) {
        $image_file = $target_dir . basename($_FILES["product_image"]["name"]);
        move_uploaded_file($_FILES["product_image"]["tmp_name"], $image_file);
        $image_url = $image_file;
    }

    // ค่าเริ่มต้นให้แสดงสินค้าคือ `1` (เปิดใช้งาน)
    $is_show = 1;

    // บันทึกข้อมูลลงฐานข้อมูล
    $stmt = $conn->prepare("INSERT INTO products (store_id, product_name, price, options, extra_cost, image_url, notes, is_show) 
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isdssssi", $store_id, $product_name, $price, $options, $extra_costs, $image_url, $notes, $is_show);

    if ($stmt->execute()) {
        echo "เพิ่มสินค้าสำเร็จ!";
        header("Location: shop_all_product.php"); // กลับไปที่หน้าหลัก
        exit();
    } else {
        echo "เกิดข้อผิดพลาด: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
