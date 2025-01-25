<?php
session_start();
require 'db_connection.php';

$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
$total_price = array_sum(array_column($cart, 'price'));
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Page</title>
    <link rel="stylesheet" href="รายการคำสั่งซื้อ.css">
</head>
<body>
    <div class="header">รายการคำสั่งซื้อ</div>

    <div class="tabs">
        <div class="tab active">ออเดอร์</div>
        <div class="tab">ที่ต้องจัดเตรียม</div>
        <div class="tab">เสร็จสิ้น</div>
    </div>

    <div class="order-list">
        <?php if (empty($cart)): ?>
            <p>Your cart is empty.</p>
        <?php else: ?>
            <?php foreach ($cart as $index => $item): ?>
                <div class="order-card">
                    <div class="order-header">
                        <span>Order: <?php echo $index + 1; ?></span>
                        <span><?php echo htmlspecialchars($item['price']); ?> ฿</span>
                    </div>
                    <div class="order-details">
                        <p><?php echo htmlspecialchars($item['name']); ?></p>
                    </div>
                    <form action="OrderSummary.php" method="post">
                        <input type="hidden" name="index" value="<?php echo $index; ?>">
                        <button type="submit" class="accept-btn">รับออเดอร์</button>
                    </form>
                </div>
            <?php endforeach; ?>
            <p>Total Price: <?php echo $total_price; ?> ฿</p>
        <?php endif; ?>
    </div>

    <div class="footer">
        <div class="footer-icon">
            🏠<span>HOME</span>
        </div>
        <div class="footer-icon">
            📄<span>Orders</span>
        </div>
        <div class="footer-icon">
            🔔<span>Notifications</span>
        </div>
        <div class="footer-icon">
            📦<span>Cart</span>
        </div>
    </div>
</body>
</html>