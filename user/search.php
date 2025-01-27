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

    <title>RMUTP Food</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/js/all.min.js" crossorigin="anonymous">
    </script>

    <style>
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
        background-color: #fff;
    }

    .header {
        background-color: #FFD400;
        padding: 10px;
        display: flex;
        justify-content: center;
        align-items: center;
        text-align: center;
    }

    .header form {
        margin: 0 auto;
        align-items: center;
        justify-content: center;
        width: 80%;
    }

    .header input {
        border: none;
        padding: 10px;
        border-radius: 20px;
        width: 70%;
        font-size: 14px;
    }

    .header .user-icon {
        height: 30px;
        cursor: pointer;
    }

    .banner {
        display: flex;
        justify-content: center;
        align-items: center;
        margin: 10px 20px;
    }

    .banner-img {
        width: 100%;
        /* ปรับขนาดให้เต็มความกว้าง */
        max-height: 200px;
        /* กำหนดความสูงสูงสุด */
        object-fit: contain;
        /* ใช้ contain เพื่อไม่ให้รูปภาพยืด */
        border-radius: 10px;
        /* กำหนดมุมโค้ง */
    }

    .categories {
        display: flex;
        justify-content: space-around;
        padding: 10px;
        background-color: #ffeb99;
    }

    .category {
        text-align: center;
    }

    .category button {
        background: #fff;
        border: none;
        padding: 15px 20px;
        border-radius: 10px;
        font-size: 1.5em;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 80px;
        height: 80px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    .category button i {
        font-size: 2rem;
        /* ขนาดไอคอน */
        color: #333;
    }

    .category p {
        margin-top: 8px;
        font-size: 14px;
        color: #333;
        font-weight: bold;
    }

    .categories button svg {
        font-size: 2rem;
        color: #333;
    }

    .category {
        text-align: center;
    }

    .category img {
        width: 50px;
        height: 50px;
    }

    .category p {
        margin-top: 5px;
        font-size: 14px;
        color: #333;
    }

    /* Recommended Shops Section */
    .recommended {
        margin: 20px;
    }

    .recommended h3 {
        margin-bottom: 10px;
        font-size: 18px;
        color: #333;
    }

    .recommended .shops {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 15px;
        justify-content: center;
    }

    .shop {
        text-align: center;
        background: #f9f9f9;
        padding: 10px;
        border-radius: 10px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        max-width: 100%;
    }

    .shop img {
        width: 100%;
        max-width: 250px;
        height: auto;
        border-radius: 10px;
    }

    /* Footer Section */
    .footer {
        align-items: center;
        display: flex;
        justify-content: space-around;
        background-color: #fff;
        padding: 5px 0;
        margin-left: 20px;
        position: fixed;
        bottom: 0;
        margin-bottom: 20px;
        width: 90%;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        border-radius: 100px;
    }
    .footer-item {
    text-align: center;
    color: #FFD400;
    font-size: 1.5rem;
    position: relative;
    cursor: pointer;
}

.footer-item p {
    font-size: 0.9rem;
    font-weight: bold;
    margin: 5px 0 0;
}

.footer-item.active {
    background-color: #FFD400;
    border-radius: 100px;
    padding: 10px 20px;
    color: #fff;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
    display: flex;
    align-items: center;
}

.notification-badge {
    position: absolute;
    top: -5px;
    right: -5px;
    width: 10px;
    height: 10px;
    background-color: red;
    border-radius: 50%;
}

    .footer div {
        text-align: center;
    }

    .footer img {
        width: 30px;
    }

    .footer p {
        margin-top: 5px;
        font-size: 12px;
    }

    .footer button {
        background: none;
        border: none;
        font-size: 1.5em;
        cursor: pointer;
    }

    .search-form {
        width: 100%;
        max-width: 500px;
        position: relative;
    }

    .search-box {
        display: flex;
        align-items: center;
        position: relative;
        border-radius: 20px;
        background-color: #fff;
        border: 1px solid #ccc;
        overflow: hidden;
    }

    .search-box input {
        flex: 1;
        border: none;
        padding: 10px 15px;
        border-radius: 20px;
        font-size: 14px;
        outline: none;
    }

    .search-box button {
        border: none;
        background: none;
        cursor: pointer;
        padding: 10px 15px;
        color: #555;
    }

    .search-box button i {
        font-size: 16px;
    }
    </style>
</head>

<body>
    <!-- Header Section -->
    <div class="header">
        <form method="GET" action="search.php" class="search-form">
            <div class="search-box">
                <input type="text" name="query" placeholder="ค้นหาสินค้า"
                    value="<?php echo isset($_GET['query']) ? htmlspecialchars($_GET['query']) : ''; ?>">
                <button type="submit"><i class="fas fa-search"></i></button>
            </div>
        </form>
    </div>

    <!-- Recommended Shops Section -->
    <div class="recommended">
        <h3>ผลการค้นหา</h3>
        <div class="shops">
            <?php while ($store_row = $store_result->fetch_assoc()): ?>
            <div class="shop">
                <a href="store_products.php?store_id=<?php echo $store_row['id']; ?>">
                    <?php
                $image_path = !empty($store_row['image']) && file_exists($store_row['image']) ? $store_row['image'] : 'default_image.jpg';
            ?>
                    <img src="<?php echo htmlspecialchars($image_path); ?>"
                        alt="<?php echo htmlspecialchars($store_row['name']); ?>" />
                    <p><?php echo htmlspecialchars($store_row['name']); ?></p>
                </a>
            </div>
            <?php endwhile; ?>
        </div>
    </div>
</body>

</html>