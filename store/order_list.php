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

// คำสั่ง SQL สำหรับดึงข้อมูลออเดอร์
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
    <title>รายการออเดอร์</title>
    <style>
        .order-item {
            border: 1px solid #ccc;
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 5px;
            position: relative;
        }

        .item-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }

        .status {
            color: #fff;
            background-color: #28a745;
            padding: 5px 10px;
            border-radius: 5px;
            font-size: 14px;
            font-weight: bold;
        }

        .item-details {
            margin-top: 10px;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            font-family: Arial, sans-serif;
            margin-top: 4rem;
        }

        .header {
            font-size: 24px;
            margin-bottom: 20px;
        }

        .btn-complete {
            display: inline-block;
            background-color: #28a745;
            color: #fff;
            border: none;
            padding: 10px 20px;
            text-align: center;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
        }

        .btn-complete:hover {
            background-color: #218838;
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
        <div class="header">รายการออเดอร์</div>
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo '<div class="order-item">';
                echo '<div class="item-header">';
                echo '<span class="status">' . $row["status"] . '</span>';
                echo '<strong>Cart ID: ' . $row["cart_id"] . '</strong>';
                echo '</div>';
                echo '<div class="item-details">';
                echo '<p>สินค้า: ' . $row["product_name"] . '</p>';
                echo '<p>เบอร์โทรผู้ใช้: ' . $row["user_phone"] . '</p>';
                echo '<p>จำนวน: ' . $row["quantity"] . '</p>';
                echo '<p>ยอดรวม: ' . number_format($row["total_price"], 2) . '฿</p>';
                echo '<button class="btn-complete" onclick="markComplete(' . $row["cart_id"] . ')">เสร็จสิ้น</button>';
                echo '</div>';
                echo '</div>';
            }
        } else {
            echo "<p>ไม่มีข้อมูลออเดอร์</p>";
        }

        $conn->close();
        ?>
    </div>

 <script>
    function markComplete(cartId) {
    if (confirm('คุณต้องการเปลี่ยนสถานะเป็น "Preparing" หรือไม่?')) {
        fetch('update_status.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ cart_id: cartId, status: 'Preparing' })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('สถานะอัปเดตเรียบร้อยแล้ว!');
                window.location.href = 'home.php'; // เปลี่ยนไปหน้า home.php
            } else {
                alert('เกิดข้อผิดพลาด: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('ไม่สามารถเชื่อมต่อกับเซิร์ฟเวอร์ได้');
        });
    }
}

</script>

</body>
</html>
