<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เมนูอาหาร</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
    /* General Styles */
    body {
        font-family: Arial, sans-serif;
        background-color: #fff;
        margin: 0;
        padding: 0;
        display: flex;
        justify-content: center;
    }

    .container {
        width: 100%;
        background-color: white;
        height: 100vh;
        display: flex;
        flex-direction: column;
        position: relative;
    }

    /* Food Image */
    .food-img {
        width: 100%;
        height: 250px;
        object-fit: cover;
        margin-top: 3.5rem;
    }

    /* Food Details */
    .food-details {
        padding: 2rem;
    }

    .food-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 18px;
        font-weight: bold;
    }

    /* Options */
    .option {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 20px 0;
        border-bottom: 1px solid #ddd;
        font-size: 16px;
        color: black;
    }

    .option label {
        display: flex;
        align-items: center;
        gap: 5px;
        margin-left: 1rem;
    }

    /* Input Field */
    .input-box {
        width: 95%;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 5px;
        margin-top: 5px;
        font-size: 14px;
    }

    /* Quantity Selector */
    .quantity-selector {
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 1rem;
    }

    .qty-btn {
        width: 30px;
        height: 30px;
        border: 1px solid #ddd;
        background: #fff;
        font-size: 16px;
        cursor: pointer;
        border-radius: 5px;
        color: #FFDE59;
    }

    .qty-value {
        padding: 0 15px;
        font-size: 16px;
        font-weight: bold;
    }

    /* Add to Cart Button */
    .btn-bottom {
        position: absolute;
        bottom: 20px;
        left: 50%;
        transform: translateX(-50%);
        width: 90%;
        border: none;
        padding: 1rem 1rem 0;
        font-size: 16px;
        font-weight: bold;
        border-radius: 30px;
        cursor: pointer;
        text-align: center;
    }

    .add-to-cart {
       
        width: 90%;
        background-color: #FFDE59;
        border: none;
        padding: 15px;
        font-size: 16px;
        font-weight: bold;
        border-radius: 30px;
        cursor: pointer;
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

    .food-choice {
        margin-top: 1rem;
        font-size: 16px;
    }

    .food-note {
        margin-top: 1rem;
        font-size: 16px;
        margin-bottom: 1rem;
    }

    </style>
</head>

<body>
    <div class="top-tab">
    <i class="fa-solid fa-arrow-left"></i>
    </div>

    <div class="container">


        <!-- Food Image -->
        <img src="images/ไก่ต้ม.jpg" alt="ข้าวมันไก่ทอด" class="food-img">

        <!-- Food Details -->
        <div class="food-details">
            <div class="food-header">
                <span>ข้าวมันไก่ต้ม</span>
                <span>50฿</span>
            </div>

            <div class="food-choice">
                <span><strong>ตัวเลือก</strong></span>
                <span>ไม่จำเป็นต้องระบุ</span>
            </div>

            <div class="option">
                <label><input type="checkbox"> พิเศษ</label>
                <span>+10</span>
            </div>
            <div class="food-note">
                <span><strong>หมายเหตุถึงร้าน</strong></span>
                <span>ไม่จำเป็นต้องระบุ</span>
            </div>
            <textarea class="input-box" placeholder="ระบุรายละเอียดเพิ่มเติม"></textarea>
        </div>

        <div class="btn-bottom">
            <div class="quantity-selector">
                <button class="qty-btn">-</button>
                <span class="qty-value">1</span>
                <button class="qty-btn">+</button>
            </div>
            <button class="add-to-cart">เพิ่มไปยังตะกร้า - 50</button>
        </div>
    </div>
</body>

</html>