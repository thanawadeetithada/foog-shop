<?php
session_start();

// ส่วนที่เชื่อมต่อกับฐานข้อมูล
$host = 'localhost'; // เซิร์ฟเวอร์ฐานข้อมูล
$dbname = 'store_management'; // ชื่อฐานข้อมูล
$username = 'root'; // ชื่อผู้ใช้ฐานข้อมูล
$password = ''; // รหัสผ่านฐานข้อมูล

// สร้างการเชื่อมต่อกับฐานข้อมูล
$conn = new mysqli($host, $username, $password, $dbname);

// ตรวจสอบการเชื่อมต่อ
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// ตรวจสอบการส่งข้อมูล
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $upload_dir = 'uploads/'; // โฟลเดอร์เก็บไฟล์ที่อัปโหลด
    $allowed_types = ['image/jpeg', 'image/png', 'image/jpg']; // ประเภทไฟล์ที่อนุญาต
    $max_file_size = 5 * 1024 * 1024; // ขนาดไฟล์สูงสุด (5MB)

    // ตรวจสอบว่ามีการอัปโหลดไฟล์หรือไม่
    if (isset($_FILES['payment_proof']) && $_FILES['payment_proof']['error'] === UPLOAD_ERR_OK) {
        $file = $_FILES['payment_proof'];
        $file_type = mime_content_type($file['tmp_name']);
        $file_size = $file['size'];

        // ตรวจสอบประเภทไฟล์
        if (!in_array($file_type, $allowed_types)) {
            $error_message = "ประเภทไฟล์ไม่ถูกต้อง (กรุณาอัปโหลดไฟล์ JPEG หรือ PNG เท่านั้น)";
        }
        // ตรวจสอบขนาดไฟล์
        elseif ($file_size > $max_file_size) {
            $error_message = "ไฟล์มีขนาดใหญ่เกินไป (ไม่เกิน 5MB)";
        } else {
            $file_name = uniqid() . '-' . basename($file['name']); // ตั้งชื่อไฟล์ใหม่
            $upload_path = $upload_dir . $file_name;

            // ตรวจสอบและย้ายไฟล์ไปยังโฟลเดอร์ที่กำหนด
            if (move_uploaded_file($file['tmp_name'], $upload_path)) {
                $user_id = 1; // เปลี่ยนเป็น user_id จริงจาก session
                $amount = 60.00; // กำหนดจำนวนเงิน
                $payment_status = 'confirmed'; // กำหนดสถานะเป็น "confirmed"

                // บันทึกข้อมูลไฟล์และสถานะลงในฐานข้อมูล
                if ($stmt = $conn->prepare("INSERT INTO payment_proofs (user_id, file_name, amount, payment_status) VALUES (?, ?, ?, ?)")) {
                    $stmt->bind_param("isds", $user_id, $file_name, $amount, $payment_status);

                    if ($stmt->execute()) {
                        $success_message = "อัปโหลดหลักฐานการชำระเงินสำเร็จ!";
                    } else {
                        $error_message = "เกิดข้อผิดพลาดในการบันทึกข้อมูล: " . $stmt->error;
                    }
                } else {
                    $error_message = "เกิดข้อผิดพลาดในการเตรียมคำสั่ง SQL: " . $conn->error;
                }

                header("Location: thank_you.php");
                exit;
            } else {
                $error_message = "ไม่สามารถอัปโหลดไฟล์ได้";
            }
        }
    } else {
        $error_message = "กรุณาเลือกไฟล์เพื่ออัปโหลด";
    }
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>อัปโหลดหลักฐานการชำระเงิน</title>
    <link rel="stylesheet" href="payment_confirmation.css">
</head>
<body>
    <div class="container">
        <h1>อัปโหลดหลักฐานการชำระเงิน</h1>

        <!-- แสดงข้อความแจ้งเตือน -->
        <?php if (!empty($error_message)): ?>
            <p style="color: red;"><?php echo htmlspecialchars($error_message); ?></p>
        <?php elseif (!empty($success_message)): ?>
            <p style="color: green;"><?php echo htmlspecialchars($success_message); ?></p>
        <?php endif; ?>

        <!-- ส่วนแสดง QR Code -->
        <div class="payment-section">
            <h2>กรุณาชำระเงิน</h2>
            <img src="จ่ายเงิน.jpg" alt="QR Code สำหรับการชำระเงิน" style="max-width: 300px; display: block; margin: 0 auto;">
            <p style="text-align: center;">สแกน QR Code เพื่อชำระเงิน</p>
        </div>

        <!-- ฟอร์มอัปโหลด -->
        <form action="payment_confirmation.php" method="post" enctype="multipart/form-data">
            <label for="payment_proof">แนบหลักฐานการชำระเงิน (JPEG, PNG):</label>
            <input type="file" name="payment_proof" id="payment_proof" required>
            <button type="submit">ยืนยันการชำระเงิน</button>
        </form>
    </div>
</body>
</html>
