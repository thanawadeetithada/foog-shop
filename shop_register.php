<?php
session_start();
include 'db.php';

$role = 'guest';
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $stmt = $conn->prepare("SELECT role FROM users WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($role);
    $stmt->fetch();
    $stmt->close();
}

// เปลี่ยนข้อความตาม role ของผู้ใช้
$title_text = ($role === 'admin') ? "เพิ่มร้านค้า" : "ลงทะเบียน";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $store_name = trim($_POST['store_name']);
    $owner_name = trim($_POST['owner_name']);
    $phone = trim($_POST['phone']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $category = $_POST['category'];

    $check_stmt = $conn->prepare("SELECT phone FROM users WHERE phone = ?");
    $check_stmt->bind_param("s", $phone);
    $check_stmt->execute();
    $check_stmt->store_result();

    if ($check_stmt->num_rows > 0) {
        echo "<script>alert('เบอร์โทรศัพท์นี้ถูกใช้งานแล้ว! กรุณาใช้เบอร์อื่น'); window.history.back();</script>";
        exit();
    }
    $check_stmt->close();

    $target_dir = "uploads/";
    $image_file = basename($_FILES["image"]["name"]);
    $image_url = $target_dir . $image_file;
    $image_file_type = strtolower(pathinfo($image_url, PATHINFO_EXTENSION));
    $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];

    if (!in_array($image_file_type, $allowed_types)) {
        echo "<script>alert('อนุญาตเฉพาะไฟล์ JPG, JPEG, PNG, GIF เท่านั้น'); window.history.back();</script>";
        exit();
    }

    if (!move_uploaded_file($_FILES["image"]["tmp_name"], $image_url)) {
        echo "<script>alert('เกิดข้อผิดพลาดในการอัปโหลดรูปภาพ'); window.history.back();</script>";
        exit();
    }

    $stmt = $conn->prepare("INSERT INTO users (phone, password, role) VALUES (?, ?, 'store_owner')");
    $stmt->bind_param("ss", $phone, $password);
    if ($stmt->execute()) {
        $owner_id = $conn->insert_id;

        $stmt = $conn->prepare("INSERT INTO stores (store_name, owner_id, owner_name, category, phone, image_url) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sissss", $store_name, $owner_id, $owner_name, $category, $phone, $image_url);
        if ($stmt->execute()) {
            echo "<script>alert('ลงทะเบียนร้านค้าเรียบร้อย!'); window.location.href = 'index.php';</script>";
            exit();
        } else {
            echo "<script>alert('เกิดข้อผิดพลาดในการลงทะเบียนร้านค้า'); window.history.back();</script>";
        }
    } else {
        echo "<script>alert('เกิดข้อผิดพลาดในการสร้างบัญชีเจ้าของร้าน'); window.history.back();</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title_text; ?></title>
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
        background-color: #FDDF59;
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

    h2 {
        color: #000;
        font-size: 2rem;
        margin-bottom: 1rem;
    }

    form button {
        width: 100%;
        padding: 0.75rem;
        background-color: #fff;
        color: #000;
        border: 2px solid #000;
        border-radius: 25px;
        font-size: 1rem;
        cursor: pointer;
        margin-top: 1rem;
        transition: background-color 0.3s ease;
    }

    .forgot-password {
        text-align: right;
        margin: 5px 0;
    }

    .forgot-password a {
        color: #000;
        text-decoration: none;
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
        padding-top: 7rem;
        height: 100vh;
    }

    form input[type="tel"],
    form input[type="text"],
    form input[type="password"] {
        width: 100%;
        padding: 0.75rem;
        margin: 0.5rem 0;
        border-radius: 25px;
        border: 1px solid #ccc;
        outline: none;
        font-size: 1rem;
        color: #333;
        transition: border 0.3s ease;
    }

    form select {
        width: 100%;
        padding: 0.75rem;
        margin: 0.5rem 0;
        border-radius: 25px;
        border: 1px solid #ccc;
        outline: none;
        font-size: 1rem;
        color: #757575;
        transition: border 0.3s ease;
    }

    form input[type="tel"]:focus,
    form input[type="text"]:focus,
    form input[type="password"]:focus,
    form select:focus {
        border-color: #f6a821;
    }

    .import-img {
        width: 100%;
        padding: 0.6rem;
        margin: 0.5rem 0;
        border-radius: 25px;
        border: 1px solid #ccc;
        outline: none;
        font-size: 1rem;
        color: #333;
        transition: border 0.3s ease;
        background-color: #ffffff;
    }
    </style>
</head>

<body>
    <div class="top-tab"></div>
    <div class="login-wrapper">
        <h2 class="login-title"><?php echo $title_text; ?></h2>
        <div class="login-container">
            <form method="POST" action="" enctype="multipart/form-data">
                <input type="text" name="store_name" placeholder="ชื่อร้าน" required>
                <input type="text" name="owner_name" placeholder="ชื่อเจ้าของร้าน" required>
                <input type="tel" name="phone" placeholder="เบอร์โทร" required pattern="[0-9]{10}" maxlength="10"
                    oninput="this.value = this.value.replace(/[^0-9]/g, ''); this.setCustomValidity('');"
                    oninvalid="this.setCustomValidity('กรุณาใส่เบอร์โทรให้ถูกต้อง (ตัวเลข 10 หลัก)');">
                <input type="password" name="password" placeholder="รหัสผ่าน" required>
                <select name="category">
                    <option value="" selected disabled>หมวดหมู่</option>
                    <option value="อาหาร">อาหาร</option>
                    <option value="เครื่องดื่ม">เครื่องดื่ม</option>
                    <option value="ของหวาน">ของหวาน</option>
                    <option value="อื่นๆ">อื่นๆ</option>
                </select>
                <div class="import-img">
                    <input type="file" name="image" id="image" required>
                </div>
                <button type="submit"><?php echo ($role === 'admin') ? "เพิ่มร้านค้า" : "ลงทะเบียน"; ?></button>
            </form>
            <br>
            <?php if ($role !== 'admin') : ?>
                <a href="index.php">เข้าสู่ระบบ</a>
            <?php endif; ?>
        </div>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            let select = document.querySelector("select[name='category']");
            select.addEventListener("change", function () {
                if (this.value === "") {
                    this.style.color = "#757575";
                } else {
                    this.style.color = "#333";
                }
            });
        });
    </script>
</body>

</html>