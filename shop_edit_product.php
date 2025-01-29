<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เข้าสู่ระบบผู้ใช้</title>
    <style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        color: black;
        text-decoration: none;
    }

    body {
        font-family: 'Sarabun', sans-serif !important;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        background-color: #fff;
    }

    .login-container {
        display: flex;
        align-items: center;
        align-content: space-between;
        justify-content: center;
        padding: 2rem;
        width: 90%;
        transition: box-shadow 0.3s ease;
    }

    .login-container:hover {
        box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.3);
    }

    h2 {
        color: #000;
        font-size: 2rem;
        margin-bottom: 1rem;
    }

    form input[type="text"],
    form input[type="password"] {
        width: 100%;
        padding: 0.75rem;
        margin: 0.5rem 0;
        border-radius: 25px;
        border: 1px solid black;
        outline: none;
        font-size: 1rem;
        color: #333;
        transition: border 0.3s ease;
    }

    form input[type="text"]:focus,
    form input[type="password"]:focus {
        border-color: #f6a821;
    }

    form button {
        width: 100%;
        padding: 0.75rem;
        background-color: #00bf62;
        color: #000;
        border: 0px;
        border-radius: 25px;
        font-size: 1rem;
        cursor: pointer;
        margin-top: 1rem;
        transition: background-color 0.3s ease;
    }

    .cancel {
        width: 100%;
        padding: 0.75rem;
        background-color: #e93125;
        color: #000;
        border: 0px;
        border-radius: 25px;
        font-size: 1rem;
        cursor: pointer;
        margin-top: 1rem;
        transition: background-color 0.3s ease;
    }

    form button:hover {
        background-color: #f0f0f0;
    }

    .forgot-password {
        text-align: right;
        margin: 5px 0;
    }

    .forgot-password a {
        color: #000;
        text-decoration: none;
    }

    .forgot-password a:hover {
        text-decoration: underline;
    }

    .register-link a {
        color: #fff;
        text-decoration: none;
    }

    .register-link a:hover {
        text-decoration: underline;
    }

    p {
        margin-top: 15px;
        font-size: 0.9rem;
        color: #000;
    }

    .login-title {
        color: #000;
        font-size: 2rem;
        margin-bottom: 2rem;
        text-align: left;
        width: 100%;
        padding-left: 20px;
    }

    .login-wrapper {
        display: flex;
        flex-direction: column;
        align-items: center;
        height: 100vh;

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

    .import-img {
        width: 100%;
        padding: 0.6rem;
        margin: 0.5rem 0;
        border-radius: 25px;
        border: 1px solid black;
        outline: none;
        font-size: 1rem;
        color: #333;
        transition: border 0.3s ease;
    }

    .header {
        margin-top: 2rem;
        color: #333;
        padding: 0px;
        font-size: 1.5em;
    }

    .option-row {
        display: flex;
        justify-content: space-between;
        gap: 10px;
        margin-bottom: 10px;
    }

    .option-row .option {
        width: 70%;
    }

    .option-row .extra {
        width: 30%
    }
    </style>
</head>

<body>
    <div class="login-wrapper">
        <div class="header">แก้ไขสินค้า</div>
        <div class="login-container">
            <form action="user_register.php" method="POST">
                <span>ชื่อสินค้า : </span>
                <input type="text" name="shop_name" placeholder="ข้าวมันไก่ต้ม" required>
                <span>ราคา (บาท) : </span>
                <input type="text" name="owner_name" placeholder="50" required>
                <div class="option-row">
                    <span>ตัวเลือกเพิ่มเติม :</span>
                    <span>ค่าใช้จ่ายเพิ่มเติม :</span>
                </div>
                <div class="option-row">
                    <input class="option" type="text" name="option1" placeholder="ธรรมดา" required>
                    <input class="extra" type="text" name="extra2" placeholder="0" required>
                </div>
                <div class="option-row">
                    <input class="option" type="text" name="option2" placeholder="พิเศษ" required>
                    <input class="extra" type="text" name="extra2" placeholder="10" required>
                </div>

                <span>รูปภาพสินค้า : </span>
                <div class="import-img">
                    <input type="file" name="payment_proof" id="payment_proof" required>
                </div>
                <span>หมายเหตุ : </span>
                <input type="text" name="owner_name" placeholder="เช่น ข้าวน้อย, ไม่เอาแตงกวา" required>
                <br>
                <button type="submit">บันทึก</button>
                <button class="cancel" type="submit">ยกเลิก</button>

            </form>
            <br>
        </div>
    </div>
</body>

</html>