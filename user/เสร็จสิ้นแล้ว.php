<?php
// ตั้งค่าการเชื่อมต่อฐานข้อมูล
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "store_management";

// สร้างการเชื่อมต่อ
$conn = new mysqli($servername, $username, $password, $dbname);

// ตรวจสอบการเชื่อมต่อ
if ($conn->connect_error) {
    die("การเชื่อมต่อล้มเหลว: " . $conn->connect_error);
}

// ดึงข้อมูลคำสั่งซื้อและหมายเลขโทรศัพท์ของผู้ใช้
$sql = "SELECT o.id, o.status, o.created_at, o.total_price, u.phone 
        FROM orders o
        JOIN users u ON o.user_id = u.id";
$result = $conn->query($sql);

session_start();
// ดึงข้อมูลตะกร้าสินค้าจาก session
$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
$total_price = array_sum(array_column($cart, 'total_price')); // คำนวณยอดรวมจากตะกร้า
?>
<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>สถานะคำสั่งซื้อ</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
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

    .details-bottom {
        position: sticky;
        bottom: 0;
        background-color: #fff;
        padding: 20px;
    }

    .header {
        margin-top: 5rem;
        color: #333;
        padding: 10px;
        font-size: 1.5em;
    }

    .order {
        padding: 15px;
        border-bottom: 1px solid #ddd;
    }

    .order:last-child {
        border-bottom: none;
    }

    .order-status {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .status {
        font-weight: bold;
    }

    .status.pending {
        color: orange;
    }

    .status.completed {
        color: #0FBE19;
    }

    .details {
        padding: 15px;
        font-size: 1.2rem;
    }

    .details strong {
        display: block;
    }

    .reorder-button {
        display: block;
        text-align: center;
        background-color: #ffd700;
        color: #333;
        text-decoration: none;
        padding: 10px;
        border-radius: 15px;
        font-size: 1.2rem;
    }

    .reorder-button:hover {
        background-color: #ffc107;
    }

    .step {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 15px;
    }

    .step .circle {
        font-size: 2rem;
    }

    .step .line {
        flex-grow: 1;
        height: 2px;
        margin: 0 10px;
        border-top: 5px dotted #0FBE19;
        margin-bottom: 25px;
    }


    .step .line.active {
        border-top: 5px dotted #0FBE19;
        margin-bottom: 25px;
    }

    .status.pending {
        color: orange;
    }

    .status.completed {
        color: #0FBE19;
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

    .circle {
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
    }

    .circle span {
        font-size: 16px;
        color: #333;
        margin-top: 10px;
    }

    .order-info {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 0;
    }

    .order-right {
        margin-left: auto;
    }

    ul {
        padding: 0;
        list-style-type: none;
    }
    </style>
</head>

<body>
    <div class="top-tab"></div>

    <div class="container">
        <div class="order-content">
            <div class="header">อาหารเสร็จสิ้นแล้ว</div>
            <?php while ($row = $result->fetch_assoc()): ?>
            <div class="step">
                <?php
                $status = $row['status'];
                $step_class = ['active', 'active', 'active', '', ''];
                if ($status == 'กำลังเตรียม') {
                    $step_class = ['active', 'active', 'active', 'active', ''];
                } elseif ($status == 'กำลังจัดส่ง') {
                    $step_class = ['active', 'active', 'active', 'active', 'active'];
                }
            ?>
                <div class="circle <?= $step_class[0] ?>">
                    <i class="fa-solid fa-circle-check" style="color: #0FBE19;"></i>
                    <span>รับออเดอร์</span>
                </div>
                <div class="line <?= $step_class[1] ?>"></div>
                <div class="circle <?= $step_class[2] ?>">
                    <i class="fa-solid fa-circle-check" style="color: #0FBE19;"></i>
                    <span>กำลังเตรียม</span>
                </div>
                <div class="line <?= $step_class[3] ?>"></div>
                <div class="circle <?= $step_class[4] ?>">
                    <i class="fa-solid fa-circle-check" style="color: #0FBE19;"></i>
                    <span>เสร็จสิ้นแล้ว</span>
                </div>
            </div>
            <div class="details">
                <div class="order-info">
                    <span><strong><?= htmlspecialchars($row['created_at']) ?></strong></span>
                    <span class="order-right"><strong>Order : <?= $row['id'] ?></strong></span>
                </div>
                <span style="display: inline-flex;align-items: center;margin-bottom: 10px;">
                    <i class="fa-solid fa-circle-user" style="margin-right: 5px;"></i>
                    <strong><?= !empty($row['phone']) ? htmlspecialchars($row['phone']) : 'ไม่มีข้อมูล'; ?></strong>
                </span>
                <hr>
                <ul>
                    <span><strong>รายการคำสั่งซื้อ</strong></span>
                    <li style="display: flex; justify-content: space-between;margin-top: 20px;">
                        <span style="width: 50%;">ข้าวมันไก่ต้ม</span>
                        <div style="display: flex; flex-direction: column; align-items: flex-end; width: 25%;">
                            <span>50.00฿</span>
                            <span>x2</span>
                        </div>
                    </li>
                </ul>
            </div>
            <?php endwhile; ?>
        </div>

        <div class="details-bottom">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <h2 style="margin-bottom: 0px;"><strong>ยอดชำระ</strong></h2>
                <h2 style="color: red; margin-bottom: 0px;"><strong>100.00฿</strong></h2>
            </div>
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <p><strong>วิธีการชำระ</strong></p>
                <p>QR Promptpay</p>
            </div>
            <hr>
            <br>
            <a href="#" class="reorder-button">สั่งซื้ออีกครั้ง</a>
        </div>

    </div>
</body>