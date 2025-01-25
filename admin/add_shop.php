<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เพิ่มร้านค้า</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* ปรับพื้นหลังและฟอนต์ของทั้งหน้า */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f8f9fa;
            color: #333;
            margin: 0;
            padding: 0;
        }

        /* ตั้งค่าคอนเทนเนอร์หลัก */
        .container {
            width: 100%;
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            color: #007bff;
        }

        /* ข้อความผิดพลาด */
        .error-message {
            color: red;
            font-weight: bold;
            text-align: center;
            margin-bottom: 20px;
        }

        /* รูปแบบฟอร์ม */
        form {
            display: flex;
            flex-direction: column;
        }

        form input {
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
        }

        form input:focus {
            border-color: #007bff;
            outline: none;
        }

        /* ปุ่ม submit */
        button {
            padding: 12px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 18px;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }

        /* ปรับสไตล์ของ link */
        a {
            text-decoration: none;
            color: #007bff;
            text-align: center;
            display: block;
            margin-top: 20px;
        }

        a:hover {
            color: #0056b3;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>เพิ่มร้านค้า</h2>

    <?php
    if (isset($_SESSION['error_message'])) {
        echo '<p class="error-message">' . $_SESSION['error_message'] . '</p>';
        unset($_SESSION['error_message']);
    }
    ?>

    <form method="post">
        <input type="text" name="shop_name" placeholder="ชื่อร้าน" required>
        <input type="text" name="owner_name" placeholder="ชื่อเจ้าของร้าน" required>
        <input type="text" name="phone" placeholder="เบอร์โทร" required>
        <input type="password" name="password" placeholder="รหัสผ่าน" required>
        <button type="submit">เพิ่มร้านค้า</button>
    </form>

    <a href="addstore.php">กลับสู่หน้าสินค้า</a>
</div>

</body>
</html>
