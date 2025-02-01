<?php
include 'db.php'; 
// รับค่า orders_status_id จาก URL
$orders_status_id = isset($_GET['orders_status_id']) ? $_GET['orders_status_id'] : 0;

// คำสั่ง SQL เพื่อดึงข้อมูลจากฐานข้อมูล โดยเชื่อมโยงตาราง orders_status, orders_status_items, products และ users
$sql = "SELECT os.created_at, os.orders_status_id, os.status_order, os.payment_method, os.total_price, 
               osi.quantity, osi.notes AS item_notes, p.product_name, p.price, u.phone
        FROM orders_status os
        LEFT JOIN orders_status_items osi ON os.orders_status_id = osi.orders_status_id
        LEFT JOIN products p ON osi.product_id = p.product_id
        LEFT JOIN users u ON os.user_id = u.user_id  -- เชื่อมโยงกับตาราง users
        WHERE os.orders_status_id = $orders_status_id";

$result = $conn->query($sql);

// ตรวจสอบว่ามีข้อมูลหรือไม่
if ($result->num_rows > 0) {
    // ดึงข้อมูลมาใช้งาน
    $row = $result->fetch_assoc();

    // สร้างตัวแปรเพื่อเก็บข้อมูลที่ต้องการแสดง
    $created_at = date("d M y, H:i", strtotime($row['created_at'])); // แปลงวันที่ให้เป็นรูปแบบที่ต้องการ
    $order_id = str_pad($row['orders_status_id'], 3, "0", STR_PAD_LEFT); // ใช้คำสั่ง str_pad เพื่อแสดงเลขออเดอร์ 3 หลัก
    $status_order = $row['status_order'];
    $payment_method = $row['payment_method'];
    $total_price = $row['total_price'];
    $quantity = $row['quantity'];
    $product_name = $row['product_name'];
    $product_price = $row['price'];
    $item_notes = $row['item_notes'];
    $user_phone = $row['phone']; // ดึงเบอร์โทรศัพท์จากตาราง users
} else {
    echo "ไม่พบข้อมูลคำสั่งซื้อ";
}

if (empty($status_order)) {
    $status_message = "รับออเดอร์"; // หาก status_order เป็น NULL หรือค่าว่าง
} elseif ($status_order == "receive") {
    $status_message = "กำลังเตรียม"; // ถ้า status_order เป็น "receive"
} elseif ($status_order == "prepare") {
    $status_message = "เสร็จสิ้น"; // ถ้า status_order เป็น "prepare"
} elseif ($status_order == "complete") {
    $status_message = "เรียบร้อย"; // ถ้า status_order เป็น "complete"
} else {
    $status_message = "สถานะไม่ทราบ"; // กรณีอื่นๆ ที่ไม่ตรงเงื่อนไขข้างต้น
}
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>สถานะคำสั่งซื้อ</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
        background-color: #f8f8f8;
    }

    .container {
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        height: 100vh;
        background: #fff;
        border-radius: 10px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        overflow-y: auto;
        padding: 0px 20px;
    }

    .details-bottom {
        position: sticky;
        bottom: 0;
        background-color: #fff;
        padding: 20px;
    }

    .header {
        margin-top: 5rem;
        color: #333;
        padding: 10px;
        font-size: 1.5em;
    }

    .order {
        padding: 15px;
        border-bottom: 1px solid #ddd;
    }

    .order:last-child {
        border-bottom: none;
    }

    .order-status {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .status {
        font-weight: bold;
    }

    .status.pending {
        color: orange;
    }

    .status.completed {
        color: #0FBE19;
    }

    .details {
        padding: 15px;
        font-size: 1.2rem;
    }

    .details strong {
        display: block;
    }

    .reorder-button {
        display: block;
        text-align: center;
        background-color: #7ed956;
        color: #333;
        text-decoration: none;
        padding: 10px;
        border-radius: 15px;
        font-size: 1.2rem;
    }

    .reorder-button:hover {
        background-color: #ffc107;
    }

    .step {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 15px;
    }

    .step .circle {
        font-size: 2rem;
    }

    .step .line {
        flex-grow: 1;
        height: 2px;
        margin: 0 10px;
        border-top: 5px dotted #ddd;
        margin-bottom: 25px;
    }

    .step .line.active {
        border-top: 5px dotted #0FBE19;
        margin-bottom: 25px;
    }

    .status.pending {
        color: orange;
    }

    .status.completed {
        color: #0FBE19;
    }

    .top-tab {
        width: 100%;
        padding: 20px;
        background-color: #FDDF59;
        position: fixed;
        top: 0;
        left: 0;
        z-index: 1000;
    }

    .circle {
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
    }

    .circle span {
        font-size: 16px;
        color: #333;
        margin-top: 10px;
    }

    .order-info {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 0;
    }

    .order-right {
        margin-left: auto;
    }

    ul {
        padding: 0;
        list-style-type: none;
    }
    </style>
</head>

<body>
    <div class="top-tab">
        <i class="fa-solid fa-arrow-left" onclick="window.location.href='shop_order.php';" style="cursor: pointer;"></i>

    </div>

    <div class="container">
        <div class="order-content">
            <div class="header">รายการคำสั่งซื้อ</div>

            <div class="details">
                <div class="order-info">
                    <span><strong><?php echo $created_at; ?></strong></span>
                    <span class="order-right"><strong>Order : <?php echo $order_id; ?></strong></span>
                </div>
                <span style="display: inline-flex;align-items: center;margin-bottom: 10px;">
                    <i class="fa-solid fa-circle-user" style="margin-right: 5px;"></i>
                    <strong><?php echo $user_phone; ?></strong>
                </span>
                <hr>
                <ul>
                    <li style="display: flex; justify-content: space-between;margin-top: 20px;">
                        <span style="width: 50%;"><?php echo $product_name; ?></span>
                        <div style="display: flex; flex-direction: column; align-items: flex-end; width: 25%;">
                            <span><?php echo number_format($product_price, 2); ?>฿</span>
                            <span>x<?php echo $quantity; ?></span>
                        </div>
                    </li>
                    <span style="color:#e1e1e1;">หมายเหตุ : <?php echo $item_notes ? $item_notes : '-'; ?></span>
                </ul>
            </div>

        </div>

        <div class="details-bottom">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <h2 style="margin-bottom: 0px;"><strong>ยอดชำระ</strong></h2>
                <h2 style="color: red; margin-bottom: 0px;">
                    <strong><?php echo number_format($total_price, 2); ?>฿</strong>
                </h2>
            </div>
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <p><strong>วิธีการชำระ</strong></p>
                <p><?php echo $payment_method; ?></p>
            </div>
            <hr>
            <br>
            <a href="<?php echo ($status_message == "เรียบร้อย") ? 'shop_order.php' : 'update_status.php?orders_status_id=' . $orders_status_id; ?>"
                class="reorder-button <?php echo ($status_message == "เรียบร้อย") ? 'finsh' : ''; ?>"
                <?php echo ($status_message == "เรียบร้อย") ? 'style="background-color: #ccc; cursor: not-allowed;"' : ''; ?>>
                <?php echo $status_message; ?>
            </a>
        </div>

    </div>

</body>

</html>

<?php
// ปิดการเชื่อมต่อฐานข้อมูล
$conn->close();
?>