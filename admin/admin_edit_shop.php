<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เข้าสู่ระบบผู้ใช้</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/js/all.min.js" crossorigin="anonymous">
    </script>
    <style>
    * {
        margin: 0;
        /* ไม่มีระยะขอบ */
        padding: 0;
        /* ไม่มีระยะขอบภายใน */
        box-sizing: border-box;
        /* ให้ขนาดรวมระยะขอบและการ padding */
        color: black;
    }

    body {
        font-family: 'Sarabun', sans-serif !important;
        /* ฟอนต์สำหรับข้อความ */
        display: flex;
        /* ใช้ flexbox */
        justify-content: center;
        /* จัดให้เนื้อหาตรงกลางในแนวนอน */
        align-items: center;
        /* จัดให้เนื้อหาตรงกลางในแนวตั้ง */
        height: 100vh;
        /* ความสูงเต็มของ viewport */
        background-color: #fff;
        /* พื้นหลังสีขาว */
    }

    .login-container {
        background-color: #FDDF59;
        /* สีพื้นหลัง */
        padding: 2rem;
        width: 90%;
        max-width: 400px;
        border-radius: 15px;
        text-align: center;
        box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
        transition: box-shadow 0.3s ease;
    }

    .login-container:hover {
        box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.3);
        /* เงาที่ลึกขึ้นเมื่อชี้ไปที่กล่อง */
    }

    .login-wrapper h2 {
        font-size: 2rem;
        color: black;
        text-align: center;
    }

    form input[type="text"],
    form input[type="password"] {
        width: 100%;
        /* ความกว้าง 100% */
        padding: 0.75rem;
        /* ระยะขอบภายใน */
        margin: 0.8rem 0;
        /* ระยะขอบด้านบนและด้านล่าง */
        border-radius: 25px;
        /* มุมมน */
        border: 1px solid #ccc;
        /* เพิ่มกรอบเพื่อให้มองเห็นได้ดีขึ้น */
        outline: none;
        /* ไม่ให้กรอบภายนอก */
        font-size: 1rem;
        /* ขนาดฟอนต์ */
        color: #333;
        /* สีข้อความ */
        transition: border 0.3s ease;
        /* การเปลี่ยนแปลงกรอบแบบเรียบ */
    }

    form input[type="text"]:focus,
    form input[type="password"]:focus {
        border-color: #f6a821;
        /* เปลี่ยนสีกรอบเมื่อมีการโฟกัส */
    }

    form .btn-confirm {
        width: 100%;
        padding: 0.75rem;
        background-color: #00BD5F;
        color: #000;
        border: 0px;
        border-radius: 25px;
        font-size: 1rem;
        cursor: pointer;
        margin-top: 0.8rem;
    }

    
    form .btn-cancel {
        width: 100%;
        padding: 0.75rem;
        background-color: #d43e3f;
        color: #000;
        border: 0px;
        border-radius: 25px;
        font-size: 1rem;
        cursor: pointer;
        margin-top: 0.8rem;
    }

    .forgot-password {
        text-align: right;
        /* จัดข้อความไปทางขวา */
        margin: 5px 0;
        /* ระยะขอบด้านบนและด้านล่าง */
    }

    .forgot-password a {
        color: #000;
        /* เปลี่ยนสีลิงก์ "ลืมรหัสผ่าน" เป็นสีดำ */
        text-decoration: none;
        /* ไม่มีขีดเส้นใต้ */
    }

    .forgot-password a:hover {
        text-decoration: underline;
        /* ขีดเส้นใต้เมื่อชี้ที่ลิงก์ */
    }

    .register-link a {
        color: #fff;
        /* เปลี่ยนสีลิงก์ "ลงทะเบียน" เป็นสีขาว */
        text-decoration: none;
        /* ไม่มีขีดเส้นใต้ */
    }

    .register-link a:hover {
        text-decoration: underline;
        /* ขีดเส้นใต้เมื่อชี้ที่ลิงก์ */
    }

    p {
        margin-top: 15px;
        /* ระยะขอบด้านบน */
        font-size: 0.9rem;
        /* ขนาดฟอนต์สำหรับข้อความ */
        color: #000;
        /* เปลี่ยนสีข้อความเป็นสีดำ */
    }

    .login-title {
        color: #000;
        /* สีข้อความ */
        font-size: 2rem;
        /* ขนาดฟอนต์ */
        margin-bottom: 2rem;
        /* ระยะห่างด้านล่าง */
        text-align: left;
        /* จัดข้อความไปทางซ้าย */
        width: 100%;
        /* ให้ความกว้างเต็มหน้าจอ */
        padding-left: 20px;
    }

    .login-wrapper {
        display: flex;
        flex-direction: column;
        /* จัดเรียงแนวตั้ง */
        align-items: center;
        /* จัดให้อยู่กึ่งกลางในแนวนอน */
        margin-top: 13rem;
        height: 100vh;
        /* ความสูงเต็มจอ */

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
    </style>
</head>

<body>
    <div class="top-tab">
    <i class="fa-solid fa-arrow-left"></i>
    </div>
    <div class="login-wrapper">
        <h2 class="login-title">แก้ไขข้อมูลร้านค้า</h2>
        <div class="login-container">
            <form action="user_login_db.php" method="POST">
                <input type="text" name="phone" placeholder="ชื่อร้าน" required>
                <input type="password" name="password" placeholder="ชื่อเจ้าของร้าน" required>
                <input type="password" name="password" placeholder="หมวดหมู่" required>
                <button class="btn-confirm" type="submit">ยืนยัน</button>
                <button class="btn-cancel"type="button">ยกเลิก</button>
            </form>
        </div>
    </div>
</body>

</html>