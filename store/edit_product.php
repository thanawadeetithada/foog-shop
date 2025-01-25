<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST["id"];
    $name = $_POST["name"];
    $price = $_POST["price"];
    $category = $_POST["category"];
    $special_option = $_POST["special_option"];

    // คำนวณราคาพิเศษ
    if ($special_option == 'special') {
        $price += 10; // เพิ่มราคาพิเศษ
    }

    // เชื่อมต่อฐานข้อมูล
    $conn = new mysqli("localhost", "root", "", "store_management");

    if ($conn->connect_error) {
        die("การเชื่อมต่อฐานข้อมูลล้มเหลว: " . $conn->connect_error);
    }

    // การจัดการการอัปโหลดไฟล์รูปภาพ
    $image_name = null;
    if (isset($_FILES["image"]) && $_FILES["image"]["error"] == UPLOAD_ERR_OK) {
        $image_name = basename($_FILES["image"]["name"]);
        $upload_dir = "uploads/";
        if (!move_uploaded_file($_FILES["image"]["tmp_name"], $upload_dir . $image_name)) {
            die("เกิดข้อผิดพลาดในการอัปโหลดไฟล์");
        }
    }

    // อัปเดตข้อมูลสินค้า
    if ($image_name) {
        // กรณีที่มีการอัปเดตรูปภาพ
        $query = "UPDATE products SET name = ?, price = ?, category = ?, special_option = ?, image = ? WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sdsssi", $name, $price, $category, $special_option, $image_name, $id);
    } else {
        // กรณีที่ไม่มีการอัปเดตรูปภาพ
        $query = "UPDATE products SET name = ?, price = ?, category = ?, special_option = ? WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sdssi", $name, $price, $category, $special_option, $id);
    }

    if ($stmt->execute()) {
        $stmt->close();
        $conn->close();
        // เปลี่ยนเส้นทางกลับไปยังหน้าแสดงสินค้าและแสดงข้อความสำเร็จ
        header("Location: หน้าแสดงสินค้า.php?success=1");
        exit;
    } else {
        die("เกิดข้อผิดพลาดในการอัปเดตข้อมูล: " . $stmt->error);
    }
}
?>