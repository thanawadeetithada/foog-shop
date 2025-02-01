<?php
$cart_order_id = isset($_GET['cart_order_id']) ? intval($_GET['cart_order_id']) : 0;

if ($cart_order_id == 0) {
    die("เกิดข้อผิดพลาด: ไม่พบหมายเลขคำสั่งซื้อ");
}
?>
<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>อัปโหลดหลักฐานการชำระเงิน</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
    .top-tab {
        width: 100%;
        padding: 20px;
        background-color: #FDDF59;
        position: fixed;
        top: 0;
        left: 0;
        z-index: 1000;
    }

    html,
    body {
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
    <div class="top-tab">
        <i class="fa-solid fa-arrow-left" onclick="window.history.back();"></i>
    </div>
    <div class="container">
        <h3>ข้อมูลการชำระ</h3>
        <?php if (!empty($error_message)): ?>
        <p style="color: red;"><?php echo htmlspecialchars($error_message); ?></p>
        <?php elseif (!empty($success_message)): ?>
        <p style="color: green;"><?php echo htmlspecialchars($success_message); ?></p>
        <?php endif; ?>

        <!-- ส่วนแสดง QR Code สำหรับการชำระเงิน -->
        <div class="payment-section">
            <img src="img/QRCode.jpg" alt="QR Code สำหรับการชำระเงิน"
                style="max-width: 300px; display: block; margin: 0 auto;">
        </div>

        <!-- ฟอร์มสำหรับอัปโหลดไฟล์ -->
        <form action="update_payment.php" method="POST" enctype="multipart/form-data">
            <label for="payment_proof">หากชำระเรียบร้อยแล้ว โปรดแนบหลักฐานการชำระเงิน</label>
            <input type="file" name="payment_proof" id="payment_proof" required>
            <input type="hidden" name="cart_order_id" value="<?php echo htmlspecialchars($cart_order_id); ?>">
            <button type="submit">ยืนยันการชำระ</button>
        </form>
    </div>
</body>

</html>