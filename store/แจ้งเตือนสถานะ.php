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
        padding: 1rem;
    }

    .status-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0.5rem 1rem;
        border-bottom: 1px solid #ddd;
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
        font-size: 1.2rem;
        color: #555;
    }

    .details .status {
        font-size: 1.2rem;
        color: #4caf50;
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
    </style>
</head>

<body>
    <div class="top-tab"></div>

    <div class="container">
        <header>
            <h2>แจ้งเตือน</h2>
        </header>
        <main>
            <?php if (empty($payments)): ?>
            <p>ไม่มีข้อมูลการชำระเงินที่ยืนยันแล้ว</p>
            <?php else: ?>
            <?php foreach ($payments as $payment): ?>
            <div class="status-item">
                <div class="icon">
                    <i class="fa-solid fa-utensils"></i>
                </div>
                <div class="details">
                    <span class="order"><strong>Order : </strong> <?php echo htmlspecialchars($payment['id']); ?></span>
                    <br>
                    <span class="status">ชำระเงินแล้ว</span>
                </div>
            </div>
            <?php endforeach; ?>
            <?php endif; ?>
        </main>
    </div>
</body>

</html>