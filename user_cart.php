<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>รายการอาหารที่สั่ง</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
    /* General Styles */
    body {
        font-family: Arial, sans-serif;
        background-color: white;
        margin: 0;
        padding: 0;
        display: flex;
        justify-content: center;
        align-items: flex-start;
        min-height: 100vh;
    }

    /* Container */
    .order-container {
        background: white;
        padding: 20px;
        width: 80%;
        max-width: 400px;
        /* Remove full height for better layout with fixed footer */
        min-height: 100vh;
        padding-bottom: 100px; /* Prevent content from overlapping the footer */
    }

    .order-item {
        display: flex;
        flex-direction: row;
        justify-content: space-between;
        align-items: center;
        background: white;
        padding: 10px;
        border-radius: 10px;
        box-shadow: 0 1px 5px rgba(0, 0, 0, 0.1);
        margin-bottom: 15px;
        margin-top: 1rem;
        gap: 10px;
    }

    .order-actions {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .food-name {
        font-size: 16px;
        font-weight: bold;
    }

    .note {
        font-size: 14px;
        color: gray;
    }

    /* Quantity Controls */
    .quantity {
        display: flex;
        align-items: center;
        background: #FFDE59;
        padding: 2px 2px;
        border-radius: 20px;
    }

    .qty-btn {
        background: none;
        border: none;
        font-size: 16px;
        font-weight: bold;
        cursor: pointer;
        color: #333;
    }

    .qty-value {
        margin: 0 10px;
        font-weight: bold;
        font-size: 16px;
    }

    /* Price */
    .price {
        font-size: 16px;
        font-weight: bold;
        margin: 10px;
    }

    /* Delete Icon */
    .delete-icon {
        color: red;
        font-size: 18px;
        cursor: pointer;
    }

    .header {
        margin-top: 3.5rem;
        color: #333;
        padding: 10px;
        font-size: 1.5em;
        text-align: center;
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

    .details-bottom {
        position: fixed;
        bottom: 0;
        width: 80%;
        background-color: #fff;
        padding: 40px;
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
    </style>
</head>

<body>
    <div class="top-tab">
    <i class="fa-solid fa-arrow-left"></i>
    </div>

    <div class="order-container">
        <div class="header">รายการที่สั่งอาหาร</div>
        <div class="order-item">
            <div class="order-details">
                <p class="food-name"><strong>ข้าวมันไก่ต้ม</strong></p>
                <p class="note">หมายเหตุ :</p>
            </div>

            <div class="order-actions">
                <div class="quantity">
                    <button class="qty-btn">-</button>
                    <span class="qty-value">1</span>
                    <button class="qty-btn">+</button>
                </div>
                <p class="price">50.-</p>
                <i class="fa-solid fa-trash delete-icon"></i>
            </div>
        </div>
    </div>

    <div class="details-bottom">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <h2 style="margin-bottom: 0px;"><strong>ชำระเงินโดย</strong></h2>
        </div>
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <p>QR Promptpay</p>
        </div>
        <hr>
        <br>
        <a href="#" class="reorder-button">ยืนยันคำสั่งซื้อ 50.-</a>
    </div>

</body>

</html>
