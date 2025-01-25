<?php
session_start();
require 'db_connection.php'; // ตรวจสอบว่าไฟล์นี้มีตัวแปร $connection ที่ถูกต้อง

// ตรวจสอบการเชื่อมต่อฐานข้อมูล
if (!$connection) {
    die("Failed to connect to database.");
}

// เรียกข้อมูลจากฐานข้อมูล
$user_id = 1; // ต้องเปลี่ยนเป็น user_id ของผู้ใช้ที่ล็อกอิน
$query = "SELECT * FROM cart_items WHERE user_id = ?";
$stmt = $connection->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$cart = [];
while ($row = $result->fetch_assoc()) {
    $cart[] = [
        'name' => 'Product ' . htmlspecialchars($row['product_id']), // สมมุติชื่อสินค้า
        'price' => (float) $row['total_price'],
        'quantity' => (int) $row['quantity'],
        'special_option' => (bool) $row['special_option'],
        'image_url' => 'product-image.jpg', // ต้องเปลี่ยนเป็นที่อยู่ของภาพสินค้าจริง
        'note' => htmlspecialchars($row['note']), // เพิ่มข้อมูลหมายเหตุ
    ];
}

$total_price = 0;
foreach ($cart as $item) {
    $total_price += $item['price'] * $item['quantity'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="manage_stores.css">
    <title>Cart</title>
    <style>
        /* สไตล์ทั้งหมดเหมือนในคำถาม */
        .cart {
            width: 80%;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            background-color: #f9f9f9;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        .cart h2 {
            font-size: 24px;
            margin-bottom: 20px;
            color: #333;
        }
        .cart ul {
            list-style: none;
            padding: 0;
        }
        .cart li {
            border-bottom: 1px solid #ddd;
            padding: 15px 0;
        }
        .cart p {
            font-size: 16px;
            color: #555;
            margin: 5px 0;
        }
        .footer {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 20px;
        }
        .footer button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
        }
        .footer button:hover {
            background-color: #45a049;
        }
        .cart p strong {
            font-size: 20px;
            color: #333;
        }
        @media (max-width: 600px) {
            .cart {
                width: 95%;
            }
            .footer {
                flex-direction: column;
            }
            .footer button {
                width: 100%;
                margin-bottom: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <input type="text" placeholder="ค้นหา">
        <img src="user-icon.png" alt="User">
    </div>

    <div class="banner">
        <img src="RMUTP FOOD.jpg" alt="RMUTP Food">
    </div>

    <div class="cart">
        <h2>ตะกร้าสินค้า</h2>
        <?php if (empty($cart)): ?>
            <p>ไม่มีสินค้าในตระกร้า.</p>
        <?php else: ?>
            <ul>
                <?php foreach ($cart as $item): ?>
                    <li>
                        <p><?php echo $item['name']; ?></p>
                        <p>ราคา: <?php echo number_format($item['price'], 2); ?> ฿</p>
                        <p>หมายเหตุ: <?php echo $item['note']; ?></p>
                        <p>ตัวเลือกพิเศษ: <?php echo $item['special_option'] ? 'Yes' : 'No'; ?></p>
                        <p>จำนวน: <?php echo $item['quantity']; ?></p>
                    </li>
                <?php endforeach; ?>
            </ul>
            <p><strong>รวมทั้งหมด: <?php echo number_format($total_price, 2); ?> ฿</strong></p>
        <?php endif; ?>
    </div>

    <footer class="footer">
        <button onclick="clearCart()">ล้างตะกร้า</button>
        <form action="payment_confirmation.php" method="POST">
            <button type="submit">ชำระเงิน</button>
        </form>
    </footer>

    <script>
        function clearCart() {
            if (confirm("คุณต้องการล้างตะกร้าสินค้าหรือไม่?")) {
                location.href = "clear_cart.php";
            }
        }
    </script>
</body>
</html>
