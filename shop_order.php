<?php
session_start(); // เริ่ม session
include 'db.php'; // เชื่อมต่อฐานข้อมูล

// ตรวจสอบว่ามี user_id ใน session หรือไม่
if (!isset($_SESSION['user_id'])) {
    die("คุณยังไม่ได้เข้าสู่ระบบ");
}

$user_id = $_SESSION['user_id'];

$status_filter = isset($_GET['status']) ? $_GET['status'] : 'receive'; 

// ดึงข้อมูลออเดอร์ โดยเช็คว่า store_id ตรงกับ user_id ที่ login
$sql = "SELECT orders_status_id, store_id, total_price, status_order, created_at 
        FROM orders_status 
        WHERE store_id = ? AND status_order = ? 
        ORDER BY created_at DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("is", $user_id, $status_filter); // ใช้ parameter สำหรับ user_id และ status_filter
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
    .circle span {
        font-size: 16px;
        color: #333;
        margin-top: 10px;
        padding: 0.2rem 0.5rem;
        border-radius: 15px;

    }

    .circle .correct {
        background-color: #FDDF59;
    }

    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
        background-color: #f8f8f8;
    }

    .container {
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        height: 100vh;
        background: #fff;
        border-radius: 10px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        overflow-y: auto;
        padding: 0px 20px;
    }

    .top-tab {
        width: 100%;
        padding: 20px;
        background-color: #FDDF59;
        position: fixed;
        top: 0;
        left: 0;
        z-index: 1000;
    }

    .header {
        margin-top: 5rem;
        color: #333;
        padding: 10px;
        font-size: 1.5em;
    }

    .step {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0 15px 0 15px;
    }

    .step .circle {
        font-size: 2rem;
    }


    /* ///////////////////// */
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
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
        color: white;
        margin-bottom: 5px;
        border: 1px;
        background-color: #4caf50;
        width: fit-content;
        border-radius: 15px;
        padding: 1px 8px;
    }

    .price {
        font-size: 0.9rem;
        font-weight: bold;
        color: #ff5722;
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
        padding: 0 5px 0 0;
        /* เพิ่มช่องว่างระหว่างคอลัมน์ */
    }

    .column:last-child {
        text-align: right;
        /* จัดข้อความในคอลัมน์สุดท้ายให้อยู่ขวา */
    }



    .footer {
        align-items: center;
        display: flex;
        justify-content: space-around;
        background-color: #fff;
        padding: 5px 0;
        margin-left: 20px;
        position: fixed;
        bottom: 0;
        margin-bottom: 20px;
        width: 90%;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        border-radius: 100px;
    }

    .footer-item {
        text-align: center;
        color: #FDDF59;
        font-size: 1.5rem;
        position: relative;
        cursor: pointer;
    }

    .footer-item p {
        font-size: 0.9rem;
        font-weight: bold;
        margin: 5px 0 0;
    }

    .footer-item.active {
        background-color: #FDDF59;
        border-radius: 100px;
        padding: 10px 20px;
        color: #fff;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        display: flex;
        align-items: center;
    }

    .footer-item.active1 {
        background-color: #ffffff;
        border-radius: 100px;
        padding: 10px 10px;
        color: #fddf59;
        /* box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2); */
        display: flex;
        align-items: center;
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
    <div class="top-tab">
    <i class="fa-solid fa-arrow-left" onclick="window.location.href='shop_main.php';"></i>
    </div>
    <div class="container">
        <div class="header">รายการคำสั่งซื้อ</div>
        <div class="step">
            <div class="circle" onclick="filterOrders('receive')">
                <span class="<?php echo ($status_filter == 'receive') ? 'correct' : ''; ?>">ออเดอร์</span>
            </div>
            <div class="circle" onclick="filterOrders('prepare')">
                <span class="<?php echo ($status_filter == 'prepare') ? 'correct' : ''; ?>">ที่ต้องจัดเตรียม</span>
            </div>
            <div class="circle" onclick="filterOrders('complete')">
                <span class="<?php echo ($status_filter == 'complete') ? 'correct' : ''; ?>">เสร็จสิ้น</span>
            </div>
        </div>

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
                    $statusText = '<p class="order">รับออเดอร์แล้ว</p>';
                } elseif ($statusOrder === "prepare") {
                    $statusText = '<p class="order-prepare">กำลังเตรียม</p>';
                } elseif ($statusOrder === "complete") {
                    $statusText = '<p class="order-confirm">เสร็จสิ้นแล้ว</p>';
                } else {
                    $statusText = '<p class="order" style="color: red;">ร้านยังไม่รับออเดอร์</p>';
                }

                // ดึงข้อมูลร้านค้าของออเดอร์นี้ (มีแค่ 1 ร้าน)
                $items_sql = "SELECT s.store_name 
                            FROM orders_status_items oi
                            LEFT JOIN products p ON oi.product_id = p.product_id
                            LEFT JOIN stores s ON p.store_id = s.store_id
                            WHERE oi.orders_status_id = ? 
                            LIMIT 1"; // ดึงมาเพียง 1 แถวเท่านั้น

                $stmt = $conn->prepare($items_sql);
                $stmt->bind_param("i", $orderId);
                $stmt->execute();
                $items_result = $stmt->get_result();
                $storeData = $items_result->fetch_assoc();
                $storeName = $storeData["store_name"] ?? "ไม่ทราบชื่อร้าน";
                $stmt->close();

                // ทำให้กดได้ทุกออเดอร์ โดยไม่มีเงื่อนไข status_order
                $clickableClass = 'clickable';
                $orderLink = "onclick=\"window.location.href='shop_order_status.php?orders_status_id={$orderId}';\"";

                // แสดงผลข้อมูล
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
                        <p class='order'><i class='fa-solid fa-store'></i>&nbsp;<strong>{$storeName}</strong></p>
                        {$statusText}
                    </div>
                </div>
                <hr>";
            }
        } else {
            echo '<p style="margin-top: 20px;">ไม่มีคำสั่งซื้อ</p>';
        }

        $conn->close();
        ?>
        </main>
    </div>

    <footer class="footer">
        <div class="footer-item " onclick="window.location.href='shop_main.php'">
            <i class="fa-solid fa-house-chimney"></i>&nbsp;
            <p>HOME</p>
        </div>
        <div class="footer-item active" onclick="window.location.href='shop_order.php'">
            <i class="fa-solid fa-file-alt"></i>
        </div>
        <div class="footer-item " onclick="window.location.href='shop_notification.php'">
            <i class="fa-solid fa-bell"></i>
        </div>
        <div class="footer-item" onclick="window.location.href='shop_all_product.php'">
            <i class="fa-regular fa-folder-open"></i>
        </div>
    </footer>
    <script>
        function filterOrders(status) {
            window.location.href = window.location.pathname + "?status=" + status;
        }
    </script>

</body>

</html>