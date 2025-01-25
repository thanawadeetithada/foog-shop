<?php
session_start();
require 'db_connection.php';

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// เชื่อมต่อฐานข้อมูล
$conn = new mysqli("localhost", "root", "", "store_management");

// ตรวจสอบการเชื่อมต่อฐานข้อมูล
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// รับค่า store_id จาก query parameter
$store_id = isset($_GET['store_id']) ? intval($_GET['store_id']) : 0;

// ดึงข้อมูลร้านค้าจาก store_details โดยใช้ store_id
$query = "SELECT shop_name, owner_name, store_id FROM store_details WHERE store_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $store_id);
$stmt->execute();
$result = $stmt->get_result();
$store_details = $result->fetch_assoc();

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>หน้าร้าน</title>
  <link rel="stylesheet" href="home.css">
</head>
<body>
<style>
    /* สไตล์สำหรับเมนูสามขีด */
    .hamburger-menu {
      display: none;
      cursor: pointer;
      font-size: 30px;
      color: black; /* สีดำ */
    }

    .hamburger-menu div {
      width: 35px;
      height: 5px;
      margin: 6px 0;
      background-color: black; /* สีดำ */
      transition: 0.4s;
    }

    .menu-items {
      display: none;
      position: absolute;
      top: 50px;
      right: 20px;
      background-color: #fff;
      border: 1px solid #ccc;
      padding: 10px;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .menu-items a {
      display: block;
      padding: 8px 0;
      text-decoration: none;
      color: #333;
    }

    .menu-items a:hover {
      background-color: #f0f0f0;
    }

    .hamburger-menu.open + .menu-items {
      display: block;
    }

    /* ใช้ media query เพื่อแสดงเมนูสามขีดในขนาดจอที่เล็กลง */
    @media (max-width: 768px) {
      .hamburger-menu {
        display: block;
      }
    }
  </style>
   <!-- เพิ่มเมนูสามขีด -->
   <div class="hamburger-menu" onclick="toggleMenu()">&#9776;</div>
      <div class="menu-items">
        <a href="logout.php">ออกจากระบบ</a>
        <a href="store_page.php?store_id=<?php echo $store_id; ?>">กลับไปหน้าร้านค้า</a>
      </div>
    </header>
  <div class="container">
    <header class="header">
      <div class="store-name"><?php echo htmlspecialchars($store_details['shop_name']); ?></div>
      <div class="store-id">Store_ID: <?php echo htmlspecialchars($store_details['store_id']); ?></div>
    </header>

    <div class="profile">
      <span class="user-name"><?php echo htmlspecialchars($store_details['owner_name']); ?></span>
      <a href="edit_store.php?store_id=<?php echo $store_id; ?>" class="edit-btn">แก้ไขข้อมูล</a>
    </div>

    <section class="order-status">
      <div class="status-item">
        <span class="status-number">1</span>
        <span class="status-label">ออเดอร์</span>
      </div>
      <div class="status-item">
        <span class="status-number">0</span>
        <span class="status-label">ที่ต้องจัดเตรียม</span>
      </div>
      <div class="status-item">
        <span class="status-number">0</span>
        <span class="status-label">เสร็จสิ้นแล้ว</span>
      </div>
    </section>

    <section class="sales-summary">
      <div class="sales-header">
        <span>สรุปยอดขาย</span>
        <select class="time-range">
          <option>ระยะเวลา</option>
        </select>
      </div>
      <div class="sales-amount">
        <span>ยอดขาย (฿)</span>
        <span class="amount">1,490</span>
      </div>
      <div class="sales-chart">
        <!-- แทนที่ด้วยกราฟจริงถ้าใช้ไลบรารี -->
        <div class="bar-chart">
          <div class="bar" style="height: 40%;"></div>
          <div class="bar" style="height: 50%;"></div>
          <div class="bar" style="height: 60%;"></div>
          <div class="bar" style="height: 80%;"></div>
          <div class="bar" style="height: 90%;"></div>
        </div>
      </div>
    </section>

    <footer class="footer">
      <div class="menu-item active">HOME</div>
      <div class="menu-item">ประวัติ</div>
      <div class="menu-item">แจ้งเตือน</div>
      <div class="menu-item"><a href="หน้าแสดงสินค้า.php?store_id=<?php echo $store_id; ?>">สินค้า</a></div>
    </footer>
  </div>
  <script>
    function toggleMenu() {
      var menu = document.querySelector('.hamburger-menu');
      menu.classList.toggle('open');
    }
  </script>
</body>
</html>