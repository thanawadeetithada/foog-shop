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
  <title>แจ้งเตือน</title>
  <link rel="stylesheet" href="แจ้งเตือนชำระ.css">
</head>
<body>
  <div class="container">
    <header>
      <h1>แจ้งเตือน</h1>
    </header>
    <main>
      <?php if (empty($payments)): ?>
        <p>ไม่มีข้อมูลการชำระเงินที่ยืนยันแล้ว</p>
      <?php else: ?>
        <?php foreach ($payments as $payment): ?>
          <div class="status-item">
            <div class="icon">
              <!-- <img src="icon-user.png" alt="User Icon"> -->
            </div>
            <div class="details">
              <span class="phone">User ID: <?php echo htmlspecialchars($payment['user_id']); ?></span>
              <span class="order">Order: <?php echo htmlspecialchars($payment['id']); ?></span>
              <span class="status">ชำระเงินแล้ว</span>
            </div>
            <div class="price"><?php echo number_format($payment['amount'], 2); ?>฿</div>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </main>
  </div>
</body>
</html>
