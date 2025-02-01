<?php
session_start(); // เริ่ม session เพื่อเข้าถึงข้อมูล user_id

// เชื่อมต่อกับฐานข้อมูล (แก้ไขตามการตั้งค่าของคุณ)
include 'db.php'; 

// ตรวจสอบว่ามี user_id ใน session หรือไม่
if (!isset($_SESSION['user_id'])) {
    die("คุณยังไม่ได้เข้าสู่ระบบ");
}

$user_id = $_SESSION['user_id']; // ดึง user_id จาก session

// ดึงข้อมูลคำสั่งซื้อจากฐานข้อมูลโดยใช้ JOIN กับ table users
$sql = "
    SELECT o.orders_status_id, o.status, o.total_price, u.phone 
    FROM orders_status o
    JOIN users u ON o.user_id = u.user_id  -- เปลี่ยนจาก store_id เป็น user_id
    WHERE o.store_id = ?  -- ใช้ store_id เป็น filter ตาม user ที่เข้าสู่ระบบ
"; 

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id); // ใช้ user_id เป็นพารามิเตอร์ใน query
$stmt->execute();
$result = $stmt->get_result(); // ดึงผลลัพธ์

?>
<!DOCTYPE html>
<html lang="en">

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
        text-decoration: none;
    }

    body {
        font-family: Arial, sans-serif;
        background-color: #fff;
    }

    .container {
        display: flex;
        flex-direction: column;
        height: 100vh;
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
        align-items: center;
        justify-content: space-between;
        padding: 1rem 1rem;

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
    }

    .details .status {
        font-size: 16px;
        color: #4caf50;
        margin-left: 1rem;
        margin-right: 1rem;
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

    footer {
        background-color: #ffcc33;
        padding: 0.5rem 0;
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

    .price {
        color: orange;
        font-size: 16px;
    }

    /* Footer Section */
    .footer {
        align-items: center;
        display: flex;
        justify-content: space-around;
        background-color: #fff;
        padding: 5px 0;
        position: fixed;
        bottom: 0;
        margin-bottom: 20px;
        width: 90%;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        border-radius: 100px;
        margin-left: 20px;
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
    </style>
</head>

<body>
    <div class="top-tab"></div>
    <div class="container">
        <div class="header">แจ้งเตือน</div>
        <br>
        <main>
            <?php
    // ตรวจสอบว่า query คืนค่าผลลัพธ์หรือไม่
    if ($result->num_rows > 0) {
        // loop ผ่านทุกออเดอร์ที่ดึงมา
        while ($row = $result->fetch_assoc()) {
            $orderId = $row["orders_status_id"];
            $status = $row["status"];
            $totalPrice = number_format($row["total_price"], 2);
            $phone = $row["phone"]; // ดึงข้อมูล phone จาก table users
            
            // ตรวจสอบสถานะคำสั่งซื้อ
            if ($status == "Paid") {
                $statusText = "ชำระแล้ว"; // ถ้าสถานะคือ "Paid" ให้แสดงว่า "ชำระแล้ว"
            } else {
                $statusText = $status; // ถ้าสถานะไม่ใช่ "Paid" ก็แสดงค่า status ตรงๆ
            }
            
            // แสดงข้อมูลใน HTML และทำให้สามารถคลิกเพื่อไปยังหน้ารายละเอียด
            echo "
            <a href='shop_order_status.php?orders_status_id={$orderId}'>
                <div class='status-item'>
                    <div class='icon'>
                        <i class='fa-solid fa-utensils'></i>
                    </div>
                    <div class='details'>
                        <span class='order'><i class='fa-solid fa-circle-user'></i>&nbsp;&nbsp;<strong>{$phone}</strong></span>&nbsp;&nbsp;
                        <span class='order'><strong>Order : {$orderId}</strong></span>
                        <br>
                        &nbsp;&nbsp;<span class='status'>{$statusText}</span>
                        <span class='price'>{$totalPrice}฿</span>
                    </div>              
                </div>
            </a>
            <hr>
            ";
        }
    } else {
        echo "<p>ไม่มีคำสั่งซื้อ</p>";
    }
    ?>
        </main>
    </div>

    <footer class="footer">
        <div class="footer-item" onclick="window.location.href='shop_main.php'">
            <i class="fa-solid fa-house-chimney"></i>&nbsp;
            <p>HOME</p>
        </div>
        <div class="footer-item" onclick="window.location.href='shop_order.php'">
            <i class="fa-solid fa-file-alt"></i>
        </div>
        <div class="footer-item active" onclick="window.location.href='shop_notification.php'">
            <i class="fa-solid fa-bell"></i>
        </div>
        <div class="footer-item" onclick="window.location.href='shop_all_product.php'">
            <i class="fa-regular fa-folder-open"></i>
        </div>
    </footer>
</body>

</html>