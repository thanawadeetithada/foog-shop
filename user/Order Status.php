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

// คำสั่ง SQL สำหรับดึงข้อมูล
$sql = "SELECT id, status, created_at, total_price FROM orders";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>สถานะคำสั่งซื้อ</title>
    <!-- <link rel="stylesheet" href="สถานะคำสั่งซื้อ.css"> -->
    <style>
        body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f8f8f8;
}

.container {
    max-width: 500px;
    margin: 0 auto;
    background: #fff;
    overflow: hidden;
    margin-bottom: 20px;
    height: 100vh;
}

.header {
    margin-top: 5rem;
    color: #333;
    padding: 10px 10px 10px 2rem;
    font-size: 1.5em;
}

.order {
    padding: 15px 30px 15px 15px;
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

.order-status span {
    padding-left: 15px;
    padding-top: 15px;
    font-size: 1rem;
}

.status {
    font-weight: bold;
}

.status.pending {
    color: orange;
}

.status.completed {
    color: green;
}

.details {
    padding: 15px;
    font-size: 0.9em;
}

.details strong {
    display: block;
    margin-bottom: 5px;
}

.reorder-button {
    display: block;
    text-align: center;
    background-color: #ffd700;
    color: #333;
    text-decoration: none;
    padding: 10px;
    border-radius: 5px;
    margin: 15px auto 0;
    max-width: 200px;
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
    width: 20px;
    height: 20px;
    border-radius: 50%;
    display: inline-block;
    background-color: #ddd;
}

.step .circle.active {
    background-color: green;
}

.step .line {
    flex-grow: 1;
    height: 2px;
    background-color: #ddd;
    margin: 0 10px;
}

.step .line.active {
    background-color: green;
}
.status.pending {
    color: orange;
}
.status.completed {
    color: green;
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
    <!-- หน้า 1 -->
    <div class="container">
    <div class="top-tab"></div>

        <div class="header">รายการคำสั่งซื้อ</div>
        <?php
        if ($result->num_rows > 0) {
            // แสดงผลข้อมูลแต่ละแถว
            while ($row = $result->fetch_assoc()) {
                $status_class = strtolower($row['status']); // แปลงสถานะเป็นตัวพิมพ์เล็กเพื่อใช้กับ CSS
                echo '<div class="order">';
                echo '<div class="order-status">';
                echo '<span>' . date("d M Y, H:i", strtotime($row["created_at"])) . '</span>';
                echo '<span><strong>Order: ' . $row["id"] . '</strong></span>';
                echo '</div>';
                echo '<div class="details">';
             
                echo '<p>ยอดชำระ: ' . number_format($row["total_price"], 2) . '฿</p>';
                echo '</div>';
                echo '</div>';
            }
        } else {
            echo "<p>ไม่มีข้อมูลคำสั่งซื้อ</p>";
        }

        // ปิดการเชื่อมต่อ
        $conn->close();
        ?>
    </div>
</body>
</html>
