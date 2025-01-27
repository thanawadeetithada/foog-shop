<?php
session_start();
require 'db_connection.php';

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
                $success_message = "อัปโหลดหลักฐานการชำระเงินสำเร็จ!";
                // คุณสามารถบันทึกข้อมูลไฟล์ลงฐานข้อมูลที่นี่ได้ หากต้องการ
                // ตัวอย่าง: INSERT INTO payment_proofs (user_id, file_name, upload_time) VALUES (...)

                header("Location: thank_you.php"); // เปลี่ยนเส้นทางไปยังหน้า 'thank you'
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
    <style>
     .top-tab {
        width: 100%;
        padding: 30px;
        background-color: #FDDF59;
        position: fixed;
        top: 0;
        left: 0;
        z-index: 1000;
    }
    html, body {
    height: 100%;
    margin: 0;
    padding: 0;
    display: flex;
    flex-direction: column;
}
.container {
    margin-top: 5rem;
    flex: 1;
    display: flex;
    flex-direction: column;
    align-items: center;
    padding-bottom: 60px;
}
    h3 {
        text-align: center;
    }
    label {
        margin: 20px 10px 10px 10px;
        text-align: center;
    }
    form {
    width: 100%;
    max-width: 400px;
    display: flex;
    flex-direction: column;
}

    form input {
        margin: 10px 25px;
    }

    form button {
    padding: 10px 20px;
    background-color: #FDDF59;
    color: black;
    border: none;
    border-radius: 10px;
    cursor: pointer;
    font-size: 16px;
    transition: background-color 0.3s;
    position: fixed;
    bottom: 20px;
    left: 50%;
    transform: translateX(-50%);
    width: 90%;
    max-width: 400px;
}

    </style>
</head>
<body>
<div class="top-tab"></div>
    <div class="container">
        <h3>ข้อมูลการชำระเงิน</h3>
        <?php if (!empty($error_message)): ?>
            <p style="color: red;"><?php echo htmlspecialchars($error_message); ?></p>
        <?php elseif (!empty($success_message)): ?>
            <p style="color: green;"><?php echo htmlspecialchars($success_message); ?></p>
        <?php endif; ?>

        <!-- ส่วนแสดง QR Code สำหรับการชำระเงิน -->
        <div class="payment-section">
            <img src="จ่ายเงิน.jpg" alt="QR Code สำหรับการชำระเงิน" style="max-width: 300px; display: block; margin: 0 auto;">
        </div>

        <!-- ฟอร์มสำหรับอัปโหลดไฟล์ -->
        <form action="payment_confirmation.php" method="post" enctype="multipart/form-data">
            <label for="payment_proof">หากชำระเรียบร้อยแล้ว โปรดแนบหลักฐานการชำระเงิน</label>
            <input type="file" name="payment_proof" id="payment_proof" required>
           <br>
            <button type="submit">ยืนยันการชำระเงิน</button>
        </form>
    </div>
</body>
</html>

