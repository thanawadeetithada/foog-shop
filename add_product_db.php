<?php
session_start();
include 'db.php';

// ตรวจสอบว่า user_id ใน session มีหรือไม่
if (!isset($_SESSION['user_id'])) {
    die("กรุณาเข้าสู่ระบบก่อนทำการสั่งซื้อ");
}

$user_id = intval($_SESSION['user_id']);

// ดึง store_id ของผู้ใช้ที่ล็อกอิน
$sql = "SELECT s.store_id 
        FROM users u 
        JOIN stores s ON u.user_id = s.user_id
        WHERE u.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($store_id);
$stmt->fetch();
$stmt->close();

if (!$store_id) {
    die("ไม่พบ store_id สำหรับผู้ใช้");
}

// รับค่าจากฟอร์ม
$product_name = isset($_POST['product_name']) ? trim($_POST['product_name']) : null;
$price = isset($_POST['price']) ? floatval($_POST['price']) : 0.00;
$options = isset($_POST['option']) ? json_encode($_POST['option']) : null; // แปลง array เป็น JSON
$extra_cost = isset($_POST['extra_cost']) ? json_encode($_POST['extra_cost']) : null; // แปลง array เป็น JSON
$notes = isset($_POST['notes']) ? trim($_POST['notes']) : null;

// ตรวจสอบค่าที่รับมาว่าไม่ว่าง
if (empty($product_name) || $price <= 0) {
    die("กรุณากรอกข้อมูลสินค้าให้ครบถ้วน");
}

// ตรวจสอบว่ามีการอัปโหลดรูปภาพหรือไม่
$image_url = null;
if (isset($_FILES['product_image']) && $_FILES['product_image']['error'] == 0) {
    $target_dir = "uploads/";
    $image_name = time() . "_" . basename($_FILES["product_image"]["name"]);
    $target_file = $target_dir . $image_name;

    // ตรวจสอบประเภทไฟล์ (JPEG, PNG เท่านั้น)
    $file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    if (!in_array($file_type, ["jpg", "jpeg", "png"])) {
        die("อัปโหลดเฉพาะไฟล์ JPG หรือ PNG เท่านั้น");
    }

    if (move_uploaded_file($_FILES["product_image"]["tmp_name"], $target_file)) {
        $image_url = $target_file;
    } else {
        die("เกิดข้อผิดพลาดในการอัปโหลดรูปภาพ");
    }
}

// **เพิ่มสินค้าใหม่ลงในฐานข้อมูล**
$sql = "INSERT INTO products (store_id, product_name, price, options, extra_cost, image_url, notes) 
        VALUES (?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("isdssss", $store_id, $product_name, $price, $options, $extra_cost, $image_url, $notes);
$stmt->execute();
$stmt->close();

// **Redirect กลับไปหน้ารายการสินค้า**
header("Location: shop_all_product.php");
exit();
?>
