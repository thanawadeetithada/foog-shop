<?php
// ตั้งค่าการเชื่อมต่อฐานข้อมูล
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "store_management";

// สร้างการเชื่อมต่อ
$conn = new mysqli($servername, $username, $password, $dbname);

// ตรวจสอบการเชื่อมต่อ
if ($conn->connect_error) {
    die("การเชื่อมต่อล้มเหลว: " . $conn->connect_error);
}

// คำสั่ง SQL สำหรับดึงข้อมูลจาก cart_items, products และ user
$sql = "SELECT 
            c.id AS cart_id, 
            c.quantity, 
            c.total_price, 
            c.created_at, 
            c.special_option,
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
    <title>ข้อมูล Cart Items</title>
    <style>
        .btn-accept {
            display: inline-block;
            background-color: #28a745; /* สีเขียว */
            color: #fff;
            border: none;
            padding: 10px 20px;
            text-align: center;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 10px;
        }

        .btn-accept:hover {
            background-color: #218838; /* สีเขียวเข้มเมื่อ hover */
        }

        .cart-item {
            border: 1px solid #ccc;
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 5px;
        }

        .item-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }

        .item-details {
            margin-top: 10px;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            font-family: Arial, sans-serif;
        }

        .header {
            font-size: 24px;
            margin-bottom: 20px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">รายการสินค้าในตะกร้า</div>
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo '<div class="cart-item">';
                echo '<div class="item-header">';
                echo '<strong>Cart ID: ' . $row["cart_id"] . '</strong>';
                echo '</div>'; 
                echo '<span>' . date("d M Y, H:i", strtotime($row["created_at"])) . '</span>';
                echo '<div class="item-details">';
                echo '<p> ' . $row["product_name"] . '</p>';
                echo '<p>ตัวเลือกพิเศษ: ' . htmlspecialchars($row["special_option"], ENT_QUOTES, 'UTF-8') . '</p>';
                echo '<p>เบอร์โทรผู้ใช้: ' . $row["user_phone"] . '</p>';
                echo '<p>จำนวน: ' . $row["quantity"] . '</p>';
                echo '<p>ยอดรวม: ' . number_format($row["total_price"], 2) . '฿</p>';
                echo '</div>';
                echo '<button class="btn-accept" onclick="acceptOrder(' . $row["cart_id"] . ')">รับออเดอร์</button>';
                echo '</div>';
            }
        } else {
            echo "<p>ไม่มีข้อมูลสินค้าในตะกร้า</p>";
        }

        $conn->close();
        ?>
    </div>

    <script>
        // ฟังก์ชันสำหรับจัดการการคลิกปุ่มรับออเดอร์
        function acceptOrder(cartId) {
            if (confirm('คุณต้องการรับออเดอร์นี้หรือไม่?')) {
                // ส่งคำขอไปยังเซิร์ฟเวอร์ผ่าน Ajax
                fetch('accept_order.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ cart_id: cartId })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('รับออเดอร์สำเร็จ!');
                        // เปลี่ยนเส้นทางไปที่หน้า order_list.php
                        window.location.href = 'order_list.php';
                    } else {
                        alert('เกิดข้อผิดพลาด: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('ข้อผิดพลาด:', error);
                    alert('ไม่สามารถเชื่อมต่อกับเซิร์ฟเวอร์ได้');
                });
            }
        }
    </script>
</body>
</html>
