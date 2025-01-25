<?php
$conn = new mysqli("localhost", "root", "", "store_management");

// ตรวจสอบการเชื่อมต่อฐานข้อมูล
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id']);

    // ดึงข้อมูลสินค้าเพื่อลบรูปภาพ
    $query = "SELECT image FROM products WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();

    if ($product) {
        $imagePath = 'uploads/' . $product['image'];
        if (file_exists($imagePath)) {
            unlink($imagePath); // ลบรูปภาพ
        }

        // ลบสินค้าออกจากฐานข้อมูล
        $deleteQuery = "DELETE FROM products WHERE id = ?";
        $deleteStmt = $conn->prepare($deleteQuery);
        $deleteStmt->bind_param("i", $id);
        $deleteStmt->execute();

        if ($deleteStmt->affected_rows > 0) {
            header("Location: หน้าแสดงสินค้า.php?success=1");
            exit();
        } else {
            echo "ไม่สามารถลบสินค้าได้";
        }
    } else {
        echo "ไม่พบสินค้าที่ต้องการลบ";
    }
}
?>