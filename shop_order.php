<?php
session_start(); // เริ่ม session
include 'db.php'; // เชื่อมต่อฐานข้อมูล

if (isset($_SESSION['store_id'])) {
    $store_id = $_SESSION['store_id'];
} else {
    die('Store ID not set in session.');
}

$sql = "SELECT orders_status_id, user_id, total_price, status_order, created_at 
        FROM orders_status 
        WHERE store_id = ? 
        ORDER BY created_at DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $store_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <title>แจ้งเตือน</title>
    <style>
    /* General Reset */
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    .clickable {
        cursor: pointer;
    }

    body {
        font-family: Arial, sans-serif;
        background-color: #fff;
        display: flex;
        flex-direction: column;
        min-height: 100vh;
        /* ให้ body ครอบคลุมพื้นที่ทั้งหมด */
    }

    .container {
        display: flex;
        flex-direction: column;
        flex: 1;
        /* ให้ container ขยายเต็มพื้นที่ที่เหลือ */
    }

    header {
        padding: 1rem 1rem 0rem 1.8rem;
        font-size: 1.2rem;
        font-weight: bold;
        color: #000;
        margin-top: 4rem;
    }

    main {
        flex: 1;
        overflow-y: auto;
        padding: 0 1rem;
    }

    .status-item {
        display: flex;
        justify-content: space-between;
        padding: 1.5rem 1rem 0.5rem 1rem;

    }

    .icon {
        font-size: 1.8rem;
        margin-right: 0.5rem;
    }

    .status-item .details {
        flex: 1;
        margin-left: 10px;
    }

    .details .phone {
        font-size: 0.9rem;
        font-weight: bold;
    }

    .details .order {
        font-size: 1rem;
        color: black;
        margin-bottom: 5px;
    }

    .details .order-prepare {
        font-size: 1rem;
        color: #ff7e2e;
        margin-bottom: 5px;
    }

    .details .order-confirm {
        font-size: 1rem;
        color: #4caf50;
        margin-bottom: 5px;
    }

    .price {
        font-size: 0.9rem;
        font-weight: bold;
        color: #ff5722;
    }

    .dot {
        width: 8px;
        height: 8px;
        background-color: red;
        border-radius: 50%;
    }

    nav {
        display: flex;
        justify-content: space-around;
    }

    .nav-item {
        text-decoration: none;
        color: #000;
        font-size: 0.9rem;
        padding: 0.5rem;
    }

    .nav-item.active {
        color: #ff5722;
        font-weight: bold;
    }

    .top-tab {
        width: 100%;
        padding: 30px;
        background-color: #FDDF59;
        position: fixed;
        top: 0;
        left: 0;
        z-index: 1000;
    }

    .header {
        margin-top: 5rem;
        color: #333;
        padding: 0 0 0 30px;
        font-size: 1.5em;
    }

    .row {
        display: flex;
        /* ใช้ Flexbox */
        justify-content: space-between;
        /* กระจายพื้นที่ระหว่างคอลัมน์ */
        align-items: center;
        /* จัดให้อยู่ตรงกลางในแนวตั้ง */
        margin-bottom: 5px;
    }

    .column {


        text-align: center;
        /* จัดข้อความให้อยู่ตรงกลาง */
        padding: 0 5px;
        /* เพิ่มช่องว่างระหว่างคอลัมน์ */
    }

    .column:last-child {
        text-align: right;
        /* จัดข้อความในคอลัมน์สุดท้ายให้อยู่ขวา */
    }


    /* Footer Section */
    .footer {
        align-items: center;
        display: flex;
        justify-content: space-around;
        background-color: #fff;
        padding: 5px 0;
        width: 90%;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        border-radius: 100px;
        margin-left: 20px;
        margin-top: 20px;
        margin-bottom: 20px;
    }

    .footer-item {
        text-align: center;
        color: #FFDE59;
        font-size: 1.5rem;
        position: relative;
        cursor: pointer;
        display: flex;
        align-items: center;
    }

    .footer-item p {
        font-size: 0.9rem;
        font-weight: bold;
        margin: 5px 0 0;
    }

    .footer-item.active {
        background-color: #FFDE59;
        border-radius: 100px;
        padding: 10px 20px;
        color: #fff;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);

    }

    .notification-badge {
        position: absolute;
        top: -5px;
        right: -5px;
        width: 10px;
        height: 10px;
        background-color: red;
        border-radius: 50%;
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

    .footer button {
        background: none;
        border: none;
        font-size: 1.5em;
        cursor: pointer;
    }

    .search-form {
        width: 100%;
        max-width: 500px;
        position: relative;
    }
    </style>
</head>

<body>
    <div class="top-tab"></div>

    <div class="container">
        <div class="header">รายการคำสั่งซื้อ</div>
        <main>

            <?php
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // แปลง timestamp เป็นวันที่อ่านง่าย
        $date = date("d M Y, H:i", strtotime($row["created_at"]));
        $orderId = $row["orders_status_id"];
        $totalPrice = number_format($row["total_price"], 2);
        $statusOrder = $row["status_order"];

        // ตรวจสอบสถานะออเดอร์
        if ($statusOrder === "receive") {
            $statusText = '<p class="order-confirm">รับออเดอร์</p>';
        } elseif ($statusOrder === "prepare") {
            $statusText = '<p class="order-prepare">กำลังเตรียม</p>';
        } elseif ($statusOrder === "complete") {
            $statusText = '<p class="order-confirm">เสร็จสิ้นแล้ว</p>';
        } else {
            $statusText = '<p class="order-confirm">รับออเดอร์</p>';
        }

        $items_sql = "SELECT GROUP_CONCAT(CONCAT(p.product_name, ' x', oi.quantity) SEPARATOR '\n') AS product_names
        FROM orders_status_items oi
        LEFT JOIN products p ON oi.product_id = p.product_id
        WHERE oi.orders_status_id = ?";

$stmt = $conn->prepare($items_sql);
$stmt->bind_param("i", $orderId);
$stmt->execute();
$items_result = $stmt->get_result();
$productData = $items_result->fetch_assoc();
$productNames = $productData["product_names"] ?? "ไม่มีสินค้า";
$stmt->close();

$productNamesArray = explode("\n", $productNames);
$productNamesWithColor = '';

foreach ($productNamesArray as $index => $productName) {
    if ($index >= 1) {
        $productNamesWithColor .= "<span style='margin-left:20px;'>{$productName}</span><br>";
    } else {
        $productNamesWithColor .= "{$productName}<br>";
    }
}

$clickableClass = 'clickable';
$orderLink = "onclick=\"window.location.href='shop_order_status.php?orders_status_id={$orderId}';\"";

echo "
<div class='status-item {$clickableClass}' {$orderLink}>
<div class='icon'>
  <i class='fa-solid fa-utensils'></i>
</div>
<div class='details'>
  <div class='row'>
      <span class='column'><strong>{$date}</strong></span>
      <span class='column'><strong>Order : {$orderId}</strong></span>
      <span class='column'><strong>{$totalPrice}฿</strong></span>
  </div>
  <p class='order'><i class='fa-solid fa-box'></i>&nbsp;<strong>{$productNamesWithColor}</strong></p>
  {$statusText}
</div>
</div>
<hr>";
    }
} else {
    echo "<p>ไม่มีคำสั่งซื้อ</p>";
}

$conn->close();
?>

        </main>

    </div>

    
    <div class="footer">
        <div class="footer-item " onclick="window.location.href='shop_main.php'">
            <i class="fa-solid fa-house-chimney"></i>&nbsp;
            <p>HOME</p>
        </div>
        <div class="footer-item active" onclick="window.location.href='shop_order.php'">
            <i class="fa-solid fa-file-alt"></i>
        </div>
        <div class="footer-item " onclick="window.location.href='shop_notification.php'">
            <i class="fa-solid fa-bell"></i>
            <span class="notification-badge"></span>
        </div>
        <div class="footer-item" onclick="window.location.href='shop_all_product.php'">
            <i class="fa-regular fa-folder-open"></i>
        </div>
    </div>
</body>

</html>