<?php
$conn = new mysqli("localhost", "root", "", "store_management");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

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
        .order-item { border: 1px solid #ccc; padding: 15px; margin-bottom: 15px; border-radius: 5px; }
        .item-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px; }
        .status { color: #fff; background-color: #28a745; padding: 5px 10px; border-radius: 5px; font-size: 14px; font-weight: bold; }
        .btn-complete { background-color: #007bff; color: #fff; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; }
        .btn-complete:hover { background-color: #0056b3; }
    </style>
</head>
<body>
    <div class="container">
        <h1>รายการออเดอร์</h1>
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="order-item" data-id="<?php echo $row['cart_id']; ?>">
    <div class="item-header">
        <span class="status">สถานะ: <?php echo htmlspecialchars($row['status']); ?></span>
        <strong>Cart ID: <?php echo htmlspecialchars($row['cart_id']); ?></strong>
    </div>
    <p>สินค้า: <?php echo htmlspecialchars($row['product_name']); ?></p>
    <p>เบอร์โทรผู้ใช้: <?php echo htmlspecialchars($row['user_phone']); ?></p>
    <p>จำนวน: <?php echo htmlspecialchars($row['quantity']); ?></p>
    <p>ยอดรวม: <?php echo number_format($row['total_price'], 2); ?>฿</p>
    <button class="btn-complete" onclick="markComplete(<?php echo $row['cart_id']; ?>)">เสร็จสิ้น</button>
</div>

            <?php endwhile; ?>
        <?php else: ?>
            <p>ไม่มีข้อมูลออเดอร์</p>
        <?php endif; ?>
        <?php $conn->close(); ?>
    </div>

    <script>
        function markComplete(cartId) {
    if (confirm('คุณต้องการเปลี่ยนสถานะเป็น "รับออเดอร์" หรือไม่?')) {
        fetch('update_order_status.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ cart_id: cartId, status: 'Completed' })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('สถานะอัปเดตเรียบร้อยแล้ว!');
                // Update the status text dynamically
                const statusElement = document.querySelector(`.order-item[data-id="${cartId}"] .status`);
                if (statusElement) {
                    statusElement.textContent = 'สถานะ: Completed';
                    statusElement.style.backgroundColor = '#007bff'; // Optional styling change
                }
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
