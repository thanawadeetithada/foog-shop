<?php
session_start();

// ข้อมูลการเชื่อมต่อฐานข้อมูล
$host = "localhost";  // ชื่อโฮสต์ฐานข้อมูล
$username = "root";   // ชื่อผู้ใช้
$password = "";       // รหัสผ่าน
$dbname = "store_management";  // ชื่อฐานข้อมูล

// สร้างการเชื่อมต่อ
$connection = new mysqli($host, $username, $password, $dbname);

// ตรวจสอบการเชื่อมต่อ
if ($connection->connect_error) {
    die("Failed to connect to database: " . $connection->connect_error);
}

// ดึงข้อมูลร้านค้า รวมถึงข้อมูลภาพ
$store_query = "SELECT id, name, image FROM stores";
$store_result = $connection->query($store_query);

// ตรวจสอบว่าคำสั่ง SQL ทำงานได้หรือไม่
if (!$store_result) {
    die("Query failed: " . $connection->error); // แสดงข้อความในกรณีที่คำสั่ง SQL ผิดพลาด
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="manage_stores.css">
    <title>RMUTP Food</title>
</head>
<body>
    <!-- Header Section -->
    <div class="header">
        <form method="GET" action="search.php">
            <input type="text" name="query" placeholder="ค้นหาสินค้า" value="<?php echo isset($_GET['query']) ? htmlspecialchars($_GET['query']) : ''; ?>">
            <button type="submit">ค้นหา</button>
        </form>
    </div>

    <!-- Banner Section -->
    <div class="banner">
        <img src="RMUTP FOOD.jpg" alt="RMUTP Food" class="banner-img">
    </div>

    <!-- Categories Section -->
    <nav class="categories">
        <button>อาหาร</button>
        <button>เครื่องดื่ม</button>
        <button>ของทานเล่น</button>
        <button>อื่นๆ</button>
    </nav>

    <!-- Recommended Shops Section -->
    <div class="recommended">
        <h3>ร้านแนะนำ</h3>
        <div class="shops">
            <?php while ($store_row = $store_result->fetch_assoc()): ?>
                <div class="shop">
                    <a href="store_products.php?store_id=<?php echo $store_row['id']; ?>">
                        <!-- ตรวจสอบว่า image มีค่า หากไม่มีให้ใช้ default_image.jpg -->
                        <?php
                        $image_path = !empty($store_row['image']) && file_exists($store_row['image']) ? $store_row['image'] : 'default_image.jpg';
                        ?>
                        <img src="<?php echo htmlspecialchars($image_path); ?>" alt="<?php echo htmlspecialchars($store_row['name']); ?>" width="250" height="auto" />
                        <!-- แสดงชื่อร้านค้า -->
                        <p><?php echo htmlspecialchars($store_row['name']); ?></p>
                    </a>
                </div>
            <?php endwhile; ?>
        </div>
    </div>

    <!-- Footer Section -->
    <footer class="footer">
        <button onclick="location.href='manage_stores.php'">🏠 HOME</button>
        <button onclick="location.href='cart.php'">🛒</button>
        <button onclick="location.href='แจ้งเตือน.php'">🔔</button>  
        <button onclick="location.href='แจ้งเตือนสถานะ.php'">📜</button>
    </footer>
</body> 
</html>
