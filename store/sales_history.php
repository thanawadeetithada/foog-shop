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
    <style>
    body {
        font-family: Arial, sans-serif;
        background-color: #fff;
        /* พื้นหลังสีขาว */
        display: flex;
        /* ใช้ flexbox */
        justify-content: center;
        /* จัดเนื้อหาให้อยู่ตรงกลางในแนวนอน */
        align-items: center;
        /* จัดเนื้อหาให้อยู่ตรงกลางในแนวตั้ง */
        height: 100vh;
        /* ความสูงเต็มของ viewport */
        margin: 0;
        /* ไม่มีระยะขอบ */
    }

    .container {
        width: 90%;
        /* ความกว้าง 90% */
        max-width: 400px;
        /* ขนาดสูงสุดเท่ากับ 400px */
        background-color: #ffeb3b;
        /* สีพื้นหลังเหลือง */
        padding: 2rem;
        /* ระยะขอบภายใน */
        border-radius: 15px;
        /* มุมมน */
        box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
        /* เงาของกล่อง */
        text-align: center;
        /* จัดข้อความให้อยู่ตรงกลาง */
        transition: box-shadow 0.3s ease;
        /* การเปลี่ยนแปลงแบบเรียบ */
        height: 100vh;
    }

    .container:hover {
        box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.3);
        /* เงาที่ลึกขึ้นเมื่อชี้ไปที่กล่อง */
    }

    h1 {
        color: #000;
        font-size: 2rem;
        margin-bottom: 1rem;
        margin-top: 7rem;
    }

    form {
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    form input {
        width: 100%;
        /* ความกว้างเต็ม */
        padding: 0.75rem;
        /* ระยะขอบภายใน */
        margin: 0.5rem 0;
        /* ระยะขอบด้านบนและด้านล่าง */
        border-radius: 25px;
        /* มุมมน */
        border: none;
        /* ไม่มีกรอบ */
        outline: none;
        /* ไม่มีกรอบภายนอก */
        font-size: 1rem;
        /* ขนาดฟอนต์ */
        color: #333;
        /* สีข้อความ */
    }

    form input::placeholder {
        color: #aaa;
        /* สีของ placeholder */
    }

    .register-btn {
        width: 100%;
        /* ความกว้างเต็ม */
        padding: 0.75rem;
        /* ระยะขอบภายใน */
        margin: 1rem 0;
        /* ระยะขอบด้านบนและด้านล่าง */
        border-radius: 25px;
        /* มุมมน */
        border: 2px solid #000;
        /* กรอบสีดำ */
        background-color: #fff;
        /* พื้นหลังสีขาว */
        color: #000;
        /* สีข้อความดำ */
        font-size: 1rem;
        /* ขนาดฟอนต์ */
        cursor: pointer;
        /* เปลี่ยนเคอร์เซอร์เป็นรูปมือ */
    }

    .login-link {
        display: block;
        /* แสดงเป็นบล็อก */
        color: black;
        /* สีข้อความดำ */
        text-decoration: none;
        /* ไม่มีขีดเส้นใต้ */
        font-size: 0.9rem;
        /* ขนาดฟอนต์ */
        padding: 0.5rem 0;
        /* ระยะขอบภายใน */
        border-radius: 25px;
        /* มุมมน */
        transition: background-color 0.3s, color 0.3s;
        /* การเปลี่ยนแปลงแบบเรียบ */
    }

    .login-link.active {
        background-color: #fff;
        /* พื้นหลังสีขาวเมื่อคลิก */
        color: #000;
        /* เปลี่ยนสีฟอนต์เป็นสีดำเมื่อคลิก */
    }

    body {
        font-family: 'Arial', sans-serif;
        background-color: #f4f4f9;
        margin: 0;
        padding: 0;
        color: #333;
    }

    .container {
        height: 100vh;
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
            height: 100vh;
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

    .top-tab {
        width: 100%;
        padding: 30px;
        background-color: #FDDF59;
        position: fixed;
        top: 0;
        left: 0;
        z-index: 1000;
    }
    </style>
</head>

<body>
    <div class="top-tab"></div>

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