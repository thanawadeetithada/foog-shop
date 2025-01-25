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
    <link rel="stylesheet" href="สถานะคำสั่งซื้อ.css">
</head>
<body>
    <!-- หน้า 1 -->
    <div class="container">
        <div class="header">รายการคำสั่งซื้อ</div>
        <?php
        if ($result->num_rows > 0) {
            // แสดงผลข้อมูลแต่ละแถว
            while ($row = $result->fetch_assoc()) {
                $status_class = strtolower($row['status']); // แปลงสถานะเป็นตัวพิมพ์เล็กเพื่อใช้กับ CSS
                echo '<div class="order">';
                echo '<div class="order-status">';
                echo '<span>' . date("d M Y, H:i", strtotime($row["created_at"])) . '</span>';
                echo '<span class="status ' . $status_class . '">' . $row["status"] . '</span>';
                echo '</div>';
                echo '<div class="details">';
                echo '<strong>Order: ' . $row["id"] . '</strong>';
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
