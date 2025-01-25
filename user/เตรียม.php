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
    <link rel="stylesheet" href="สถานะคำสั่งซื้อ.css">
</head>
<body>
    <div class="container">
        <div class="header">สถานะคำสั่งซื้อ</div>
        <?php while ($row = $result->fetch_assoc()): ?>
        <div class="step">
            <?php
                // จัดการสถานะการแสดงผล
                $status = $row['status'];
                $step_class = ['active', 'active', 'active', '', '']; // กำหนดสถานะที่แสดง
                if ($status == 'กำลังเตรียม') {
                    $step_class = ['active', 'active', 'active', 'active', ''];
                } elseif ($status == 'กำลังจัดส่ง') {
                    $step_class = ['active', 'active', 'active', 'active', 'active'];
                }
            ?>
            <div class="circle <?= $step_class[0] ?>"></div>
            <div class="line <?= $step_class[1] ?>"></div>
            <div class="circle <?= $step_class[2] ?>"></div>
            <div class="line <?= $step_class[3] ?>"></div>
            <div class="circle <?= $step_class[4] ?>"></div>
        </div>
        <!-- เริ่มแสดงรายการสินค้าจากตะกร้า -->
        <div class="details">
            <strong>Order <?= $row['id'] ?></strong>
            <p><strong>วันที่คำสั่งซื้อ:</strong> <?= htmlspecialchars($row['created_at']) ?></p> <!-- แสดงวันที่คำสั่งซื้อ -->
            <p><strong>หมายเลขโทรศัพท์:</strong> <?= !empty($row['phone']) ? htmlspecialchars($row['phone']) : 'ไม่มีข้อมูล'; ?></p>
            <ul>
                <?php if (!empty($cart)): ?>
                    <?php foreach ($cart as $item): ?>
                        <li>
                            <p><strong>ชื่อสินค้า:</strong> <?= htmlspecialchars($item['name']); ?></p>
                            <p><strong>ราคา:</strong> <?= number_format($item['price'], 2); ?> ฿</p>
                            <p><strong>จำนวน:</strong> <?= htmlspecialchars($item['quantity']); ?></p>
                            <p><strong>ยอดรวม:</strong> <?= number_format($item['total_price'], 2); ?> ฿</p>
                        </li>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>ไม่มีสินค้าในตะกร้า</p>
                <?php endif; ?>
            </ul>
            <p><strong>ยอดรวมทั้งหมด: <?= number_format($total_price, 2); ?> ฿</strong></p>
        </div>
        <a href="#" class="reorder-button">สั่งซื้ออีกครั้ง</a>
        <?php endwhile; ?>
    </div>
</body>
</html>

<?php
$conn->close();
?>
