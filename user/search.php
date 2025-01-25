<?php
$servername = "localhost"; // หรือชื่อเซิร์ฟเวอร์ของคุณ
$username = "root"; // ชื่อผู้ใช้ฐานข้อมูล
$password = ""; // รหัสผ่านฐานข้อมูล
$dbname = "store_management"; // ชื่อฐานข้อมูล

// สร้างการเชื่อมต่อฐานข้อมูล
$conn = new mysqli($servername, $username, $password, $dbname);

// ตรวจสอบการเชื่อมต่อ
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}



if ($query) {
    // คำสั่ง SQL ค้นหาสินค้าจากชื่อสินค้า
    $search_query = "SELECT id, name, image FROM products WHERE name LIKE ?";
    $stmt = $conn->prepare($search_query);
    $search_term = "%$query%"; // เพิ่มเครื่องหมาย % เพื่อให้ค้นหาคำที่ตรงกันบางส่วน
    $stmt->bind_param("s", $search_term);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    // หากไม่มีคำค้นหา, ดึงสินค้าทั้งหมด
    $result = $conn->query("SELECT id, name, image FROM products");
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="manage_stores.css">
    <title>ผลการค้นหา</title>
</head>
<body>

    <!-- Header Section -->
    <div class="header">
        <form method="GET" action="search.php">
            <input type="text" name="query" placeholder="ค้นหาสินค้า" value="<?php echo isset($_GET['query']) ? htmlspecialchars($_GET['query']) : ''; ?>">
            <button type="submit">ค้นหา</button>
        </form>
    </div>

    <!-- Display Search Results -->
    <div class="recommended">
        <h3>ผลการค้นหา</h3>
        <div class="shops">
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <div class="shop">
                        <a href="store_products.php?store_id=<?php echo $row['id']; ?>">
                            <!-- ตรวจสอบว่า image มีค่า หากไม่มีให้ใช้ default_image.jpg -->
                            <?php
                            $image_path = !empty($row['image']) && file_exists($row['image']) ? $row['image'] : 'default_image.jpg';
                            ?>
                            <img src="<?php echo htmlspecialchars($image_path); ?>" alt="<?php echo htmlspecialchars($row['name']); ?>" width="250" height="auto" />
                            <!-- แสดงชื่อสินค้า -->
                            <p><?php echo htmlspecialchars($row['name']); ?></p>
                        </a>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>ไม่พบสินค้าที่คุณค้นหา</p>
            <?php endif; ?>
        </div>
    </div>

</body>
</html>
