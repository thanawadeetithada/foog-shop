<?php
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'store_management';

// เชื่อมต่อฐานข้อมูล
$connection = new mysqli($host, $username, $password, $database);

// ตรวจสอบการเชื่อมต่อ
if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

// ดึงข้อมูลจากตาราง payment_proofs
$query = "SELECT * FROM payment_proofs WHERE payment_status = 'confirmed' ORDER BY upload_time ASC";
$result = $connection->query($query);

// เก็บข้อมูลในอาร์เรย์
$payments = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $payments[] = $row;
    }
}
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

            <div class="status-item">
                <div class="icon">
                    <i class="fa-solid fa-utensils"></i>
                </div>
                <div class="details">
                    <div class="row">
                        <span class="column"><strong>22 ธ.ค. 67, 10:25</strong></span>
                        <span class="column"><strong>Order : 003</strong></span>
                        <span class="column"><strong>150฿</strong></span>
                    </div>
                    <p class="order"><i
                            class="fa-solid fa-bag-shopping"></i>&nbsp;<strong>ข้าวมันไก่ต้ม</strong>&nbsp;<span>x2</span>
                            <br>
                            <strong style="margin-left: 20px;">ข้าวมันไก่ทอด</strong>&nbsp;<span>x1</span>
                    </p>

                    <p class="order"><i class="fa-solid fa-circle-user"></i>&nbsp;<strong>0616519783</strong></p>
                    <p class="order-prepare" >กำลังเตรียม</p>
                </div>
            </div>
            <hr>
            <div class="status-item">
                <div class="icon">
                    <i class="fa-solid fa-utensils"></i>
                </div>
                <div class="details">
                    <div class="row">
                        <span class="column"><strong>17 ธ.ค. 67, 09:25</strong></span>
                        <span class="column"><strong>Order : 002</strong></span>
                        <span class="column"><strong>50฿</strong></span>
                    </div>
                    <p class="order"><i
                            class="fa-solid fa-bag-shopping"></i>&nbsp;<strong>ลูกชิ้นทอด</strong>&nbsp;<span>x1</span>
                    </p>
                    <p class="order"><i class="fa-solid fa-circle-user"></i>&nbsp;<strong>0616519783</strong></p>
                    <p class="order-confirm">เสร็จสิ้นแล้ว</p>
                </div>
            </div>
            <hr>
            <div class="status-item">
                <div class="icon">
                    <i class="fa-solid fa-utensils"></i>
                </div>
                <div class="details">
                    <div class="row">
                        <span class="column"><strong>16 ธ.ค. 67, 11:45</strong></span>
                        <span class="column"><strong>Order : 001</strong></span>
                        <span class="column"><strong>50฿</strong></span>
                    </div>
                    <p class="order"><i
                            class="fa-solid fa-bag-shopping"></i>&nbsp;<strong>ข้าวมันไก่ต้ม</strong>&nbsp;<span>x1</span>
                    </p>
                    <p class="order"><i class="fa-solid fa-circle-user"></i>&nbsp;<strong>0616519783</strong></p>
                    <p class="order-confirm">เสร็จสิ้นแล้ว</p>
                </div>
            </div>
            <hr>
        </main>
    </div>
    <footer class="footer">
        <div class="footer-item">
            <i class="fa-solid fa-house-chimney"></i>&nbsp;
            <p>HOME</p>
        </div>
        <div class="footer-item active">
            <i class="fa-solid fa-file-alt"></i>
        </div>
        <div class="footer-item ">
            <i class="fa-solid fa-cart-shopping"></i>
        </div>
        <div class="footer-item notification">
            <i class="fa-solid fa-bell"></i>
            <span class="notification-badge"></span>
        </div>
    </footer>
</body>

</html>