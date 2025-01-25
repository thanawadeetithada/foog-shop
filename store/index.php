<?php
session_start();
require 'db_connection.php';

// Check if the admin is logged in
if (!isset($_SESSION['admin_username'])) {
    header("Location: login.php");
    exit();
}

// Fetch product data
$query = "SELECT * FROM products";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Store Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Prompt:wght@300;400;500&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Prompt', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
        }

        .header {
            background-color: #ffeb3b;
            padding: 20px;
            text-align: center;
            font-size: 18px;
            font-weight: bold;
        }

        .container {
            padding: 20px;
        }

        .status-section, .summary-section {
            margin-bottom: 20px;
        }

        .status-section {
            display: flex;
            justify-content: space-between;
            gap: 10px;
        }

        .status-card {
            background-color: white;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            flex: 1;
            padding: 10px;
            text-align: center;
            border-radius: 10px;
        }

        .status-card h3 {
            margin: 0;
            font-size: 18px;
            color: #555;
        }

        .status-card p {
            font-size: 24px;
            font-weight: bold;
            margin: 5px 0;
        }

        .summary-section h3 {
            margin: 0;
            font-size: 20px;
            font-weight: bold;
        }

        .summary-card {
            margin-top: 10px;
            background-color: white;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            padding: 15px;
        }

        .chart {
            height: 200px;
            background-color: #b2ebf2;
            border-radius: 10px;
        }

        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background-color: white;
            box-shadow: 0 -2px 4px rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: space-around;
            padding: 10px 0;
        }

        .footer-item {
            text-align: center;
        }

        .footer-item img {
            width: 24px;
            height: 24px;
        }

        .footer-item span {
            display: block;
            font-size: 12px;
        }

        .product-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .product-table th, .product-table td {
            border: 1px solid #ddd;
            padding: 8px;
        }

        .product-table th {
            background-color: #f2f2f2;
            text-align: left;
        }
    </style>
</head>
<body>
    <div class="header">ร้านข้าวมันไก่</div>
    <div class="container">
        <div class="status-section">
            <div class="status-card">
                <h3>ออเดอร์</h3>
                <p>1</p>
            </div>
            <div class="status-card">
                <h3>ที่ต้องจัดเตรียม</h3>
                <p>0</p>
            </div>
            <div class="status-card">
                <h3>เสร็จสิ้นแล้ว</h3>
                <p>0</p>
            </div>
        </div>
        <div class="summary-section">
            <h3>สรุปยอดขาย</h3>
            <div class="summary-card">
                <div class="chart">Graph Placeholder</div>
            </div>
        </div>
        <div class="product-section">
            <h3>รายการสินค้า</h3>
            <table class="product-table">
                <thead>
                    <tr>
                        <th>ชื่อสินค้า</th>
                        <th>ราคา</th>
                        <th>หมวดหมู่</th>
                        <th>ตัวเลือกเพิ่มเติม</th>
                        <th>ค่าใช้จ่ายเพิ่มเติม</th>
                        <th>สถานะ</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['name']); ?></td>
                            <td><?php echo number_format($row['price'], 2); ?>฿</td>
                            <td><?php echo htmlspecialchars($row['category']); ?></td>
                            <td><?php echo htmlspecialchars($row['extra_option']); ?></td>
                            <td><?php echo number_format($row['extra_price'], 2); ?>฿</td>
                            <td><?php echo $row['is_available'] ? 'Available' : 'Not Available'; ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="footer">
    <div class="footer-item">
        <a href="index.php">
            <img src="home-icon.png" alt="Home">
            <span>HOME</span>
        </a>
    </div>
    <div class="footer-item">
        <a href="orders.php">
            <img src="orders-icon.png" alt="Orders">
            <span>Orders</span>
        </a>
    </div>
    <div class="footer-item">
        <a href="notifications.php">
            <img src="notification-icon.png" alt="Notifications">
            <span>Notifications</span>
        </a>
    </div>
    <div class="footer-item">
        <a href="profile.php">
            <img src="profile-icon.png" alt="Profile">
            <span>Profile</span>
        </a>
    </div>
    <div class="footer-item">
        <a href="หน้าแสดงสินค้า.php">
            <img src="profile-icon.png" alt="Profile">
            <span>หน้าร้านค้า</span>
        </a>
    </div>
</div>
</body>
</html>