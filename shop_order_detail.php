<?php

include 'db.php';

$orders_status_id = isset($_GET['orders_status_id']) ? $_GET['orders_status_id'] : 0;

if ($orders_status_id == 0) {
    echo "ไม่พบคำสั่งซื้อ";
    exit;
}

$sql = "
SELECT 
    os.created_at, 
    os.orders_status_id, 
    os.total_price, 
    os.payment_method, 
    os.status_order,
    osi.quantity,
    osi.subtotal,
    osi.notes,
    p.product_name,
    u.phone
FROM 
    orders_status os
JOIN 
    orders_status_items osi ON os.orders_status_id = osi.orders_status_id
JOIN 
    products p ON osi.product_id = p.product_id
JOIN 
    users u ON os.user_id = u.user_id
WHERE 
    os.orders_status_id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $orders_status_id); // "i" หมายถึง integer
$stmt->execute();

$result = $stmt->get_result();

// ถ้ามีข้อมูล
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $created_at = date("d M y, H:i", strtotime($row['created_at'])); // แปลงวันที่
    $total_price = number_format($row['total_price'], 2) . "฿";
    $payment_method = $row['payment_method'];
    $status_order = $row['status_order'];
    $quantity = $row['quantity'];
    $subtotal = number_format($row['subtotal'], 2) . "฿";
    $notes = $row['notes'];
    $product_name = $row['product_name'];
    $phone = $row['phone'];

    if ($status_order === 'receive') {
        $status_order_display = 'รับออเดอร์';
        $button_class = 'green-button';
    } elseif ($status_order === 'prepare') {
        $status_order_display = 'เสร็จสิ้น';
        $button_class = 'green-button';
    } elseif ($status_order === 'complete') {
        $status_order_display = 'เรียบร้อย';
        $button_class = 'gray-button';
    } else {
        $status_order_display = 'ยังไม่ได้รับออเดอร์';
        $button_class = 'red-button';
    }
  
} else {
    echo "ไม่พบข้อมูลคำสั่งซื้อ";
    exit;
}

// ปิดการเชื่อมต่อฐานข้อมูล
$stmt->close();
$conn->close();
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
        color: #333;
        text-decoration: none;
        padding: 10px;
        border-radius: 15px;
        font-size: 1.2rem;
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

    .green-button {
        background-color: #7ed956;
    }

    .red-button {
        background-color: #e93125;
    }

    .gray-button {
        background-color: #d1cdcc;
    }

    a:hover {
        color: inherit; /* คงสีเดิม */
        text-decoration: none;
    }
    
    </style>
</head>

<body>
    <div class="top-tab">
        <a href="shop_order.php">
            <i class="fa-solid fa-arrow-left"></i>
        </a>

    </div>

    <div class="container">
        <div class="order-content">
            <div class="header">รายการคำสั่งซื้อ</div>


            <div class="details">
                <div class="order-info">
                    <span><strong><?php echo $created_at; ?></strong></span>
                    <span class="order-right"><strong>Order : <?php echo $orders_status_id; ?></strong></span>
                </div>
                <span style="display: inline-flex;align-items: center;margin-bottom: 10px;">
                    <i class="fa-solid fa-circle-user" style="margin-right: 5px;"></i>
                    <strong><?php echo $phone; ?></strong>
                </span>
                <hr>
                <ul>
                    <li style="display: flex; justify-content: space-between;margin-top: 20px;">
                        <span style="width: 50%;"><?php echo $product_name; ?></span>
                        <div style="display: flex; flex-direction: column; align-items: flex-end; width: 25%;">
                            <span><?php echo $subtotal; ?></span>
                            <span>x<?php echo $quantity; ?></span>
                        </div>
                    </li>
                    <span style="color:#e1e1e1;">หมายเหตุ : <?php echo $notes; ?></span>
                </ul>
            </div>

        </div>

        <div class="details-bottom">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <h2 style="margin-bottom: 0px;"><strong>ยอดชำระ</strong></h2>
                <h2 style="color: red; margin-bottom: 0px;"><strong><?php echo $total_price; ?></strong></h2>
            </div>
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <p><strong>วิธีการชำระ</strong></p>
                <p><?php echo $payment_method; ?></p>
            </div>
            <hr>
            <br>
            <a href="#" class="reorder-button <?php echo $button_class; ?>"><?php echo $status_order_display; ?></a>
        </div>
    </div>
</body>