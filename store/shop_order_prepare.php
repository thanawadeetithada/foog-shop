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
        background-color: #4caf50;;
        margin-bottom: 5px;
        border: 1px;
        width: fit-content;
        border-radius: 15px;
        padding: 1px 8px;
        color: black;
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

    .top-tab {
        width: 100%;
        padding: 30px;
        background-color: #FDDF59;
        position: fixed;
        top: 0;
        left: 0;
        z-index: 1000;
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
    <div class="top-tab"></div>
    <div class="container">
        <div class="order-content">
            <div class="header">รายการคำสั่งซื้อ</div>

            <div class="step">
                <div class="circle">
                    <span >ออเดอร์</span>
                </div>
                <div class="circle">
                    <span class="correct">ที่ต้องจัดเตรียม</span>
                </div>
                <div class="circle">
                    <span >เสร็จสิ้น</span>
                </div>
            </div>

            <main>
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
                        <p style="margin-bottom: 5px;margin-left:1rem"> หมายเหตุ : - </p>
                        <p class="order"><i class="fa-solid fa-circle-user"></i>&nbsp;<strong>0616519783</strong></p>
                        <p class="order-confirm">เสร็จสิ้น</p>
                    </div>
                </div>
                <hr>
            </main>

        </div>
    </div>
    <footer class="footer">
    <div class="footer-item">
                <i class="fa-solid fa-house-chimney"></i>&nbsp;
                <p>HOME</p>
            </div>
            <div class="footer-item active">
                <i class="fa-solid fa-file-alt"></i>
            </div>
            <div class="footer-item">
                 <i class="fa-solid fa-bell"></i>
            </div>
            <div class="footer-item ">
            <i class="fa-regular fa-folder-open"></i>
            </div>
    </footer>
</body>