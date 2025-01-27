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
    <!-- <link rel="stylesheet" href="manage_stores.css"> -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/js/all.min.js" crossorigin="anonymous">
    </script>
    <title>Cart</title>
    <style>
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
        background-color: #fff;
    }

    /* Header Styling */
    .header {
        background-color: #FFD400;
        padding: 10px;
        display: flex;
        justify-content: center;
        align-items: center;
        text-align: center;
    }

    .header form {
        margin: 0 auto;
        align-items: center;
        justify-content: center;
        width: 80%;
    }

    .header input {
        border: none;
        padding: 10px;
        border-radius: 20px;
        width: 70%;
        font-size: 14px;
    }

    .header .user-icon {
        height: 30px;
        cursor: pointer;
    }


    .banner {
        display: flex;
        justify-content: center;
        align-items: center;
        margin: 10px 10px;
        margin-right: 40px;
    }

    .banner-img {
        width: 100%;
        object-fit: contain;
        border-radius: 10px;
    }

    /* Categories */
    .categories {
        display: flex;
        justify-content: space-around;
        padding: 10px;
        background-color: #ffeb99;
    }

    .categories button {
        background: #fff;
        border: none;
        padding: 10px;
        border-radius: 10px;
        font-size: 0.9em;
        cursor: pointer;
    }

    .category {
        text-align: center;
    }

    .category img {
        width: 50px;
        height: 50px;
    }

    .category p {
        margin-top: 5px;
        font-size: 14px;
        color: #333;
    }

    /* Recommended Shops Section */
    .recommended {
        margin: 20px;
    }

    .recommended h3 {
        margin-bottom: 10px;
        font-size: 18px;
        color: #333;
    }

    .recommended .shops {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 15px;
    }

    .shop {
        text-align: center;
        background: #f9f9f9;
        padding: 10px;
        border-radius: 10px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    /* Footer Section */
    .footer {
        background-color: #FFD400;
        display: flex;
        justify-content: space-around;
        padding: 10px 0;
        position: fixed;
        bottom: 0;
        width: 100%;
    }

    .footer div {
        text-align: center;
    }

    .footer img {
        width: 30px;
    }

    .footer p {
        margin-top: 5px;
        font-size: 12px;
    }

    /* Footer */
    .footer {
        display: flex;
        justify-content: space-around;
        background-color: #fff;
        padding: 10px;
        border-top: 1px solid #ddd;
    }

    .footer button {
        background: none;
        border: none;
        font-size: 1.5em;
        cursor: pointer;
    }

    .cart {
        width: 80%;
        margin: 0 auto;
        padding-left: 20px;
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
            padding-left: 30px;
            padding-top: 20px;
            flex-direction: column;
        }

        .footer button {
            width: 80%;
            margin-bottom: 10px;
            border-radius: 10px;
        }
    }

    .search-form {
        width: 100%;
        max-width: 500px;
        position: relative;
    }

    .search-box {
        display: flex;
        align-items: center;
        position: relative;
        border-radius: 20px;
        background-color: #fff;
        border: 1px solid #ccc;
        overflow: hidden;
    }

    .search-box input {
        flex: 1;
        border: none;
        padding: 10px 15px;
        border-radius: 20px;
        font-size: 14px;
        outline: none;
    }

    .search-box button {
        border: none;
        background: none;
        cursor: pointer;
        padding: 10px 15px;
        color: #555;
    }

    .search-box button i {
        font-size: 16px;
    }
    </style>
</head>

<body>
    <div class="header">
        <form method="GET" action="search.php" class="search-form">
            <div class="search-box">
                <input type="text" name="query" placeholder="ค้นหาสินค้า"
                    value="<?php echo isset($_GET['query']) ? htmlspecialchars($_GET['query']) : ''; ?>">
                <button type="submit"><i class="fas fa-search"></i></button>
            </div>
        </form>
    </div>


    <div class="banner">
        <img src="RMUTP FOOD.jpg" alt="RMUTP Food" class="banner-img">
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