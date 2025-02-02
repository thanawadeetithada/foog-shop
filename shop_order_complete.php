<?php
session_start();

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $store_id = $_SESSION['store_id'];

    echo "<script>console.log('User ID: " . $user_id . " and Store ID: " . $_SESSION['store_id'] . " logged in');</script>";
} else {
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>สถานะคำสั่งซื้อ</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>

        * {
            text-decoration: none;
        }
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
        color: black;
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
        display: none;
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

    .order-pending {
        background-color: red;
        color: black;
        font-size: 1rem;
        margin-bottom: 5px;
        border: 1px;
        width: fit-content;
        border-radius: 15px;
        padding: 1px 8px;
    }

    .order-receive {
        background-color: #7fd854;
        color: black;
        font-size: 1rem;
        margin-bottom: 5px;
        border: 1px;
        width: fit-content;
        border-radius: 15px;
        padding: 1px 8px;
    }

    .order-prepare {
        background-color: #7fd854;
        color: black;
        font-size: 1rem;
        margin-bottom: 5px;
        border: 1px;
        width: fit-content;
        border-radius: 15px;
        padding: 1px 8px;
    }

    .order-complete {
        color: #52bb4d;
        font-size: 1rem;
        margin-bottom: 5px;
        border: 1px;
        width: fit-content;
        border-radius: 15px;
        padding: 1px 8px;
    }
    </style>
</head>

<body>
    <div class="top-tab"></div>
    <div class="container">
        <div class="order-content">
            <div class="header">รายการคำสั่งซื้อ</div>

            <div class="step">
                <div class="circle">
                    <a href="shop_order.php">
                        <span>ออเดอร์</span>
                    </a>
                </div>
                <div class="circle">
                    <a href="shop_order_prepare.php">
                        <span >ที่ต้องจัดเตรียม</span>
                    </a>
                </div>
                <div class="circle">
                    <a href="shop_order_complete.php">
                        <span class="correct">เสร็จสิ้น</span>
                    </a>
                </div>
            </div>

            <main>
                <?php
// รวมไฟล์เชื่อมต่อฐานข้อมูล
include 'db.php';
$sql = "
    SELECT o.created_at, o.orders_status_id, o.total_price, p.product_name, osi.notes, o.status_order
    FROM orders_status o
    LEFT JOIN orders_status_items osi ON o.orders_status_id = osi.orders_status_id
    LEFT JOIN products p ON osi.product_id = p.product_id
    WHERE o.store_id = '" . $store_id . "' AND o.status_order = 'complete'
    ORDER BY o.created_at DESC
";

$result = $conn->query($sql);

// ตรวจสอบผลลัพธ์
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        // แปลงวันที่ให้เป็นรูปแบบที่ต้องการ
        $created_at = date("d M y, H:i", strtotime($row['created_at']));
        $order_id = $row['orders_status_id'];
        $total_price = number_format($row['total_price'], 2) . "฿";
        $product_name = $row['product_name'];
        $notes = $row['notes'] ? $row['notes'] : '-';
        $status_order = $row['status_order'];

        // กำหนดข้อความและคลาสสำหรับ status_order
        $status_class = '';
        $status_text = '';

        if (empty($status_order) || is_null($status_order)) {
            $status_text = 'ยังไม่ได้รับออเดอร์';
            $status_class = 'order-pending';  // พื้นหลังสีแดง
        } elseif ($status_order == 'receive') {
            $status_text = 'รับออเดอร์';
            $status_class = 'order-receive';  // พื้นหลังสีเขียว
        } elseif ($status_order == 'prepare') {
            $status_text = 'กำลังจัดเตรียม';
            $status_class = 'order-prepare';  // พื้นหลังสีส้ม
        } elseif ($status_order == 'complete') {
            $status_text = 'เสร็จสิ้นแล้ว';
            $status_class = 'order-complete';  // ไม่มีพื้นหลัง
        }

        // แสดงผลใน HTML
        echo '<a href="shop_order_status.php?orders_status_id=' . $order_id . '" class="order-link">';
        echo '<div class="status-item">';
        echo '<div class="icon"><i class="fa-solid fa-utensils"></i></div>';
        echo '<div class="details">';
        echo '<div class="row">';
        echo '<span class="column"><strong>' . $created_at . '</strong></span>';
        echo '<span class="column"><strong>Order : ' . str_pad($order_id, 3, '0', STR_PAD_LEFT) . '</strong></span>';
        echo '<span class="column"><strong>' . $total_price . '</strong></span>';
        echo '</div>';
        echo '<p class="order"><i class="fa-solid fa-bag-shopping"></i>&nbsp;<strong>' . $product_name . '</strong></p>';
        echo '<p style="margin-bottom: 5px;margin-left:1rem"> หมายเหตุ : ' . $notes . ' </p>';
        echo '<p class="order-confirm ' . $status_class . '">' . $status_text . '</p>';
        echo '</div>';
        echo '</div>';
        echo '</a>';
        echo '<hr>';
    }
} else {
    echo '<p style="margin-top: 20px;">ไม่พบข้อมูลคำสั่งซื้อ</p>';
}

// ปิดการเชื่อมต่อฐานข้อมูล
$conn->close();
?>

            </main>

        </div>
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
            <span class="notification-badge"></span>
        </div>
        <div class="footer-item " onclick="window.location.href='shop_all_product.php'">
            <i class="fa-regular fa-folder-open"></i>
        </div>
    </footer>
    <script>
    function fetchNotifications() {
        fetch('get_notifications_shop.php')
            .then(response => response.json())
            .then(data => {
                var hasNotification = data.includes(1);
                if (hasNotification) {
                    document.querySelector('.notification-badge').style.display = 'block';
                } else {
                    document.querySelector('.notification-badge').style.display = 'none';
                }
            })
            .catch(error => console.error('Error fetching notifications:', error));
    }

    fetchNotifications();
    setInterval(fetchNotifications, 1000);
    </script>
</body>

</html>