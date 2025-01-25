<?php
session_start();
require 'db_connection.php';

// ตรวจสอบการเข้าสู่ระบบ
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// ดึงข้อมูลสถานะคำสั่งซื้อจากฐานข้อมูล
$conn = new mysqli("localhost", "root", "", "store_management");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// ดึงข้อมูลสถานะคำสั่งซื้อ
$query = "
    SELECT 
        COUNT(*) AS total,
        SUM(CASE WHEN status = '0' THEN 1 ELSE 0 END) AS preparing,
        SUM(CASE WHEN status = '1' THEN 1 ELSE 0 END) AS completed
    FROM cart_items";
$result = $conn->query($query);
$order_status = $result->fetch_assoc();

// ดึงข้อมูลผู้ใช้งาน
$username = $_SESSION['username'];
$query = "SELECT store_id, shop_name, owner_name FROM store_details WHERE owner_name = ?";
$stmt = $conn->prepare($query);
if ($stmt === false) {
    die("Prepare failed: " . $conn->error);
}
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$store_details = $result->fetch_assoc();
if (!$store_details) {
    die("No store details found for username: " . $username);
}
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="home.css">
  <title>หน้าหลัก</title>
  <style>
    .status-item { text-align: center; margin-bottom: 15px; }
    .status-number { font-size: 24px; font-weight: bold; color: #28a745; }
    .status-label { font-size: 16px; color: #555; }
    .header { padding: 20px; background-color: #f8f9fa; text-align: center; font-size: 24px; }
    .profile { text-align: center; margin-top: 20px; font-size: 18px; }
    .order-status { margin-top: 20px; }
    .footer { text-align: center; margin-top: 20px; }
    .menu-item { display: inline-block; margin-right: 15px; font-size: 16px; }
    .menu-item a { text-decoration: none; color: #007bff; }
    .menu-item.active { font-weight: bold; color: #28a745; }
  </style>
</head>
<body>
  <div class="container">
    <header class="header">
      <div class="store-name">ร้าน: <?php echo htmlspecialchars($store_details['shop_name']); ?></div>
      <div class="store-id">Store ID: <?php echo htmlspecialchars($store_details['store_id']); ?></div>
    </header>

    <div class="profile">
      <span class="user-name">เจ้าของร้าน: <?php echo htmlspecialchars($store_details['owner_name']); ?></span>
      <a href="sales_history.php?store_id=<?php echo $store_details['store_id']; ?>" class="edit-btn">ดูประวัติการขาย</a>
    </div>

    <section class="order-status">
    <div class="status-item">
    <a href="order_list.php?store_id=<?php echo $store_details['store_id']; ?>">
        <span class="status-number" id="total-orders"><?php echo $order_status['total']; ?></span>
    </a>
    <span class="status-label">ออเดอร์ทั้งหมด</span>
</div>

      <div class="status-item">
        <a href="OrderSummary.php?store_id=<?php echo $store_details['store_id']; ?>">
          <span class="status-number" id="total-orders"><?php echo $order_status['total']; ?></span>
        </a>
        <span class="status-label">ที้่ต้องจัดเตรียม</span>
      </div>
      <div class="status-item">
        <a href="OrderSummary.php?store_id=<?php echo $store_details['store_id']; ?>">
          <span class="status-number" id="total-orders"><?php echo $order_status['total']; ?></span>
        </a>
        <span class="status-label">เสร็จแล้ว</span>
      </div>
    </section>

    <footer class="footer">
      <div class="menu-item active">HOME</div>
      <div class="menu-item"><a href="OrderSummary.php?store_id=<?php echo $store_details['store_id']; ?>">รายการคำสั่งซื้อ</a></div>
      <div class="menu-item"><a href="sales_history.php?store_id=<?php echo $store_details['store_id']; ?>">แจ้งเตือน</a></div>
      <div class="menu-item"><a href="หน้าแสดงสินค้า.php?store_id=<?php echo $store_details['store_id']; ?>">สินค้า</a></div>
    </footer>
  </div>

  <script>
    function updateOrderStatus() {
      fetch('get_order_status.php')
        .then(response => response.json())
        .then(data => {
          document.getElementById('total-orders').textContent = data.total;
          document.getElementById('preparing-orders').textContent = data.preparing;
          document.getElementById('completed-orders').textContent = data.completed;
        })
        .catch(error => console.error('Error:', error));
    }

    // รีเฟรชข้อมูลทุกๆ 5 วินาที
    setInterval(updateOrderStatus, 5000);
  </script>
</body>
</html>
