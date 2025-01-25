<?php
// Database connection settings
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "store_management";

// Establish connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch orders and user phone numbers
$sql = "SELECT o.id, o.status, o.created_at, o.total_price, u.phone 
        FROM orders o
        JOIN users u ON o.user_id = u.id";
$result = $conn->query($sql);

session_start();
// Fetch cart items from the session
$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
$total_price = array_sum(array_column($cart, 'total_price'));
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
                // Determine the active status for the steps
                $status_map = [
                    'รอดำเนินการ' => [true, false, false],
                    'กำลังเตรียม' => [true, true, false],
                    'กำลังจัดส่ง' => [true, true, true],
                    'อาหารเสร็จสิ้นแล้ว' => [true, true, true],
                ];
                $statuses = $status_map[$row['status']] ?? [false, false, false];
            ?>
            <div class="circle <?= $statuses[0] ? 'active' : '' ?>"></div>
            <div class="line <?= $statuses[1] ? 'active' : '' ?>"></div>
            <div class="circle <?= $statuses[1] ? 'active' : '' ?>"></div>
            <div class="line <?= $statuses[2] ? 'active' : '' ?>"></div>
            <div class="circle <?= $statuses[2] ? 'active' : '' ?>"></div>
        </div>
        <div class="details">
            <strong>Order <?= htmlspecialchars($row['id']) ?></strong>
            <p><strong>วันที่คำสั่งซื้อ:</strong> <?= htmlspecialchars($row['created_at']) ?></p>
            <p><strong>หมายเลขโทรศัพท์:</strong> <?= !empty($row['phone']) ? htmlspecialchars($row['phone']) : 'ไม่มีข้อมูล'; ?></p>
            <ul>
                <?php if (!empty($cart)): ?>
                    <?php foreach ($cart as $item): ?>
                        <li>
                            <?= htmlspecialchars($item['name']) ?> x<?= htmlspecialchars($item['quantity']) ?> - <?= number_format($item['total_price'], 2) ?> ฿
                        </li>
                    <?php endforeach; ?>
                <?php else: ?>
                    <li>ไม่มีสินค้าในตะกร้า</li>
                <?php endif; ?>
            </ul>
            <p><strong>ยอดรวมทั้งหมด:</strong> <?= number_format($row['total_price'], 2) ?> ฿</p>
        </div>
        <a href="#" class="reorder-button">สั่งซื้ออีกครั้ง</a>
        <?php endwhile; ?>
    </div>
</body>
</html>
<?php
$conn->close();
?>
