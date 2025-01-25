<?php
// เริ่มต้น session
session_start();

// การเชื่อมต่อฐานข้อมูล
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "store_management";

$conn = new mysqli($servername, $username, $password, $dbname);

// ตรวจสอบการเชื่อมต่อ
if ($conn->connect_error) {
    die("การเชื่อมต่อล้มเหลว: " . $conn->connect_error);
}

// ดึงข้อมูลจากตาราง cart_items, products, และ user
$sql = "SELECT 
            c.id AS cart_id, 
            c.quantity, 
            c.total_price, 
            c.status,
            p.name AS product_name, 
            u.phone AS user_phone
        FROM cart_items c
        JOIN products p ON c.product_id = p.id
        JOIN user u ON c.user_id = u.id";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ประวัติการขาย</title>
    <link rel="stylesheet" href="styles.css"> <!-- ใส่ไฟล์ CSS -->
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
            color: #333;
        }

        .container {
            width: 80%;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: #2c3e50;
            text-align: center;
            font-size: 2em;
            margin-bottom: 20px;
        }

        .sales-entry {
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .sales-entry p {
            font-size: 1.1em;
            margin: 5px 0;
        }

        .sales-entry hr {
            margin: 10px 0;
            border: 0;
            border-top: 1px solid #ddd;
        }

        .no-data {
            text-align: center;
            font-size: 1.2em;
            color: #e74c3c;
        }

        /* Styling the buttons and links */
        a {
            color: #3498db;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        /* Adding some responsive design */
        @media (max-width: 768px) {
            .container {
                width: 90%;
            }

            h1 {
                font-size: 1.5em;
            }
        }

        /* Menu styling */
        .menu-btn {
            position: fixed;
            top: 20px;
            right: 20px;
            font-size: 30px;
            cursor: pointer;
            background-color: transparent;
            border: none;
            color: #333;
        }

        .menu-content {
            position: fixed;
            top: 0;
            right: 0;
            width: 200px;
            height: 100%;
            background-color: #34495e;
            color: white;
            display: none;
            flex-direction: column;
            padding: 20px;
            box-shadow: -2px 0 10px rgba(0, 0, 0, 0.1);
        }

        .menu-content a {
            color: white;
            text-decoration: none;
            font-size: 1.2em;
            margin-bottom: 15px;
        }

        .menu-content a:hover {
            text-decoration: underline;
        }

        .menu-content.active {
            display: flex;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>ประวัติการขาย</h1>
        <?php if ($result->num_rows > 0): ?>
            <?php while($row = $result->fetch_assoc()): ?>
                <div class="sales-entry">
                    <p><strong>รหัสการขาย:</strong> <?php echo htmlspecialchars($row['cart_id']); ?></p>
                    <p><strong>สินค้า:</strong> <?php echo htmlspecialchars($row['product_name']); ?></p>
                    <p><strong>เบอร์โทรผู้ใช้:</strong> <?php echo htmlspecialchars($row['user_phone']); ?></p>
                    <p><strong>จำนวน:</strong> <?php echo htmlspecialchars($row['quantity']); ?></p>
                    <p><strong>ยอดรวม:</strong> <?php echo number_format($row['total_price'], 2); ?>฿</p>
                </div>
                <hr>
            <?php endwhile; ?>
        <?php else: ?>
            <p class="no-data">ไม่มีประวัติการขาย</p>
        <?php endif; ?>

        <?php $conn->close(); // ปิดการเชื่อมต่อฐานข้อมูล ?>
    </div>

    <!-- เมนูสามขีด -->
    <button class="menu-btn" onclick="toggleMenu()">&#9776;</button>

    <div class="menu-content" id="menu">
        <a href="login.php">ออกจากระบบ</a>
        <a href="home.php">กลับไปหน้าหลัก</a>
    </div>

    <script>
        function toggleMenu() {
            var menu = document.getElementById('menu');
            menu.classList.toggle('active');
        }

        document.addEventListener('click', function(event) {
            var menu = document.getElementById('menu');
            var menuButton = document.querySelector('.menu-btn');

            // ตรวจสอบว่าไม่ได้คลิกที่ปุ่มหรือเมนู
            if (!menu.contains(event.target) && !menuButton.contains(event.target)) {
                menu.classList.remove('active');
            }
        });
    </script>
</body>
</html>
