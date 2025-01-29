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
        background-color: #FFDE59;
        padding: 10px;
        display: flex;
        justify-content: center;
        align-items: center;
        text-align: center;
    }

    .header form {
        margin: 0 10px 0 0;
        align-items: center;
        justify-content: center;
        width: 70%;
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
        background-color: white;
    }

    .category {
        text-align: center;
    }

    .category button {
        background: #FFDE59;
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
        color: white;
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
        margin-top: 15px;
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
        height: 100%;
        max-height: 100px;
        border-radius: 10px;
    }

    /* Footer Section */
    .footer {
        align-items: center;
        display: flex;
        justify-content: space-around;
        background-color: #fff;
        padding: 5px 0;
        position: fixed;
        bottom: 0;
        margin-bottom: 20px;
        width: 90%;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        border-radius: 100px;
    }

    .footer-item {
        text-align: center;
        color: #FFDE59;
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
        background-color: #FFDE59;
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

    a {
        text-decoration: none;
        color: black;
    }

    .fa-circle-user {
        font-size: 1.8rem;
        color: #ffffff;
        background-color: #ccc;
        /* border: 1px solid #ccc; */
        border-radius: 15px;
    }

    .menu-item {
        display: flex;
        /* ใช้ Flexbox */
        justify-content: space-between;
        /* กระจายช่องว่างระหว่างเนื้อหา */
        align-items: center;
        /* จัดตำแหน่งแนวตั้ง */
        margin-left: 5px;
        margin-right: 5px;
    }

    .menu-item .shop-btn {
        background-color: #0448A9;
        border: 0px;
        padding: 0.4rem;
        border-radius: 5px;
        color: white;
    }

    .menu-item .edit-shop-btn {
        background-color: red;
        border: 0px;
        padding: 0.4rem;
        border-radius: 5px;
        color: white;
    }

    .price {
        margin-left: auto;
        /* ดันไปด้านขวาสุด */
        color: #333;
        /* สีของข้อความ */
        font-weight: bold;
        /* เน้นตัวหนา */
    }

    .search-btn {
        padding: 0.2rem 0.5rem;
        border-radius: 20px;
        border: 0px;
        background-color: #5171FF;
    }

    .details-bottom {
    position: fixed; 
    bottom: 0;
    left: 0;
    width: 90%;
    padding: 20px;
    z-index: 1000;
}

    .reorder-button {
        display: block;
        text-align: center;
        background-color: #ffd700;
        color: #333;
        text-decoration: none;
        padding: 10px;
        border-radius: 15px;
        font-size: 1.2rem;
    }

    .reorder-button:hover {
        background-color: #ffc107;
    }

    .fa-arrow-left {
        margin-right: 20px;
    }
    </style>
</head>


<body>
    <!-- Header Section -->
    <div class="header">
    <i class="fa-solid fa-arrow-left"></i>&nbsp;&nbsp;
        <form method="GET" action="search.php" class="search-form">
            <div class="search-box">
                <input type="text" name="query" placeholder="ค้นหาสินค้า"
                    value="<?php echo isset($_GET['query']) ? htmlspecialchars($_GET['query']) : ''; ?>">
                <button type="submit"><i class="fas fa-search"></i></button>
            </div>
        </form>
        <i class="fa-solid fa-circle-user"></i>
        </div>


    <!-- Recommended Shops Section -->
    <div class="recommended">
        <h3>ร้านค้า</h3>
        <div class="shops">
            <div class="shop">
                <a>
                    <img src="uploads/ไก่ต้ม.jpg">
                    <p class="menu-item">
                        <span>ร้านข้าวมันไก่</span>
                    </p>
                    <p class="menu-item">
                        <button class="shop-btn">ดูร้านค้า</button>                        
                        <button class="edit-shop-btn">แก้ไข</button>
                    </p>
                </a>
            </div>

            <div class="shop">
                <a>
                    <img src="uploads/ลูกชิ้น.jpg">
                    <p class="menu-item">
                        <span>ร้านลูกชิ้นทอด</span>
                    </p>
                    <p class="menu-item">
                        <button class="shop-btn">ดูร้านค้า</button>                        
                        <button class="edit-shop-btn">แก้ไข</button>
                    </p>
                </a>
            </div>
            <div class="shop">
                <a>
                    <img src="uploads/กาแฟโบราณ.jpg">
                    <p class="menu-item">
                        <span>ร้านกาแฟโบราณ</span>
                    </p>
                    <p class="menu-item">
                        <button class="shop-btn">ดูร้านค้า</button>                        
                        <button class="edit-shop-btn">แก้ไข</button>
                    </p>
                </a>
            </div>
        </div>

        <footer class="details-bottom">
            <a href="#" class="reorder-button">เพิ่มร้านค้า</a>
        </footer>
</body>

</html>