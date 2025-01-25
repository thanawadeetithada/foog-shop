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

// ดึงข้อมูลจากฐานข้อมูล stores และ cart_items
$query = "
    SELECT 
        ci.id AS order_id,
        ci.user_id,
        s.name AS store_name
    FROM cart_items ci
    LEFT JOIN stores s ON ci.store_id = s.id
    WHERE ci.status = 'completed'
    ORDER BY ci.id DESC -- แก้ไขเป็นการเรียงตาม id
";

$result = $connection->query($query);

// เก็บข้อมูลในอาร์เรย์
$orders = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $orders[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>แจ้งเตือน</title>
  <link rel="stylesheet" href="แจ้งเตือน.css">
</head>
<body>
  <div class="container">
    <header>
      <h1>แจ้งเตือน</h1>
    </header>
    <main>
      <?php if (empty($orders)): ?>
        <p>ไม่มีรายการที่เสร็จสมบูรณ์</p>
      <?php else: ?>
        <?php foreach ($orders as $order): ?>
          <div class="status-item">
            <div class="icon">
              <!-- <img src="icon.png" alt="Icon"> -->
            </div>
            <div class="details">
              <span class="order">Order: <?php echo htmlspecialchars($order['order_id']); ?></span>
              <span class="store">ร้าน: <?php echo htmlspecialchars($order['store_name'] ?? 'ไม่ทราบชื่อร้าน'); ?></span>
              <span class="status">เสร็จสิ้นแล้ว</span>
            </div>
            <div class="dot"></div>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </main>
  </div>
</body>
</html>
