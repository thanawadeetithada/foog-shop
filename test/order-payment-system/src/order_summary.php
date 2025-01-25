<?php
session_start();
require 'db_connection.php';

// ตรวจสอบ cart_id ที่ส่งมาจากแบบฟอร์ม
$cart_id = $_POST['cart_id'] ?? null;
if ($cart_id && $cart_id === session_id()) {
    $cart = $_SESSION['cart'] ?? [];
} else {
    $cart = [];
    echo "<p>ไม่มีข้อมูลคำสั่งซื้อในระบบ</p>";
    exit;
}

// คำนวณราคาสินค้าทั้งหมด
$total_price = array_sum(array_column($cart, 'price'));
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>รายการอาหารที่สั่ง</title>
    <link rel="stylesheet" href="css/order_summary.css">
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>รายการอาหารที่สั่ง</h1>
        </div>

        <div class="order-list">
            <?php if (empty($cart)): ?>
                <p>ไม่มีสินค้าในคำสั่งซื้อ</p>
            <?php else: ?>
                <?php foreach ($cart as $item): ?>
                    <div class="order-item">
                        <span><?php echo htmlspecialchars($item['name']); ?></span>
                        <span><?php echo htmlspecialchars($item['price']); ?> ฿</span>
                    </div>
                <?php endforeach; ?>
                <p>Total Price: <?php echo $total_price; ?> ฿</p>
            <?php endif; ?>
        </div>

        <div class="payment">
            <a href="#">QR Promptpay</a>
        </div>

        <div class="confirm-button">
            <form action="payment_confirmation.php" method="post">
                <input type="hidden" name="cart_id" value="<?php echo session_id(); ?>">
                <input type="hidden" name="total_price" value="<?php echo $total_price; ?>">
                <button type="submit">ยืนยันคำสั่งซื้อ <?php echo $total_price; ?>.-</button>
            </form>
        </div>
    </div>
</body>
</html>