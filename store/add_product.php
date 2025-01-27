<?php
session_start();
require 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // ตรวจสอบว่า 'store_id' ถูกส่งมาจากฟอร์มหรือไม่
    if (isset($_POST["store_id"]) && !empty($_POST["store_id"])) {
        $name = $_POST["name"];
        $price = $_POST["price"];
        $category = $_POST["category"];
        $extra_option = $_POST["extra_option"] ?? "";
        $extra_price = $_POST["extra_price"] ?? 0;
        $store_id = $_POST["store_id"];  // รับค่า store_id จากฟอร์ม
        $is_available = $store_id;  // ตั้งค่า is_available ให้มีค่าเดียวกันกับ store_id

        // เส้นทางโฟลเดอร์อัปโหลด
        $upload_dir = "uploads/";

        // ตรวจสอบและสร้างโฟลเดอร์ถ้ายังไม่มี
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true); // สร้างโฟลเดอร์พร้อมตั้งค่าให้เขียนได้
        }

        // ตรวจสอบสิทธิ์การเขียนในโฟลเดอร์
        if (!is_writable($upload_dir)) {
            echo "ไม่สามารถเขียนไฟล์ในโฟลเดอร์ $upload_dir ได้ กรุณาตรวจสอบสิทธิ์";
            exit;
        }

        // การจัดการอัปโหลดไฟล์
        if (isset($_FILES["image"]) && $_FILES["image"]["error"] == UPLOAD_ERR_OK) {
            $image_name = basename($_FILES["image"]["name"]);
            $target_file = $upload_dir . $image_name;

            // ย้ายไฟล์
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                // เชื่อมต่อฐานข้อมูล
                $conn = new mysqli("localhost", "root", "", "store_management");

                // ตรวจสอบการเชื่อมต่อฐานข้อมูล
                if ($conn->connect_error) {
                    die("การเชื่อมต่อฐานข้อมูลล้มเหลว: " . $conn->connect_error);
                }

                // ตรวจสอบว่า store_id ที่ได้รับมีอยู่ในตาราง stores หรือไม่
                $checkStore = $conn->prepare("SELECT id FROM stores WHERE id = ?");
                $checkStore->bind_param("i", $store_id);
                $checkStore->execute();
                $checkStore->store_result();

                if ($checkStore->num_rows > 0) {
                    // store_id มีอยู่ในตาราง stores สามารถเพิ่มสินค้าได้
                    $stmt = $conn->prepare("INSERT INTO products (store_id, name, price, category, extra_option, extra_price, is_available, image) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                    $stmt->bind_param("isdssdss", $store_id, $name, $price, $category, $extra_option, $extra_price, $is_available, $image_name);
                    $stmt->execute();

                    // ปิดการเชื่อมต่อ
                    $stmt->close();
                    echo "<script>
                            window.onload = function() {
                                document.getElementById('popup').style.display = 'block';
                                document.getElementById('popup-overlay').style.display = 'block';
                            }
                          </script>";
                    // Redirect to the product display page
                    header("Location: หน้าแสดงสินค้า.php?store_id=$store_id");
                    exit;
                } else {
                    echo "ไม่พบร้านค้าที่ระบุ";
                }

                $checkStore->close();
                $conn->close();
            } else {
                echo "การอัปโหลดรูปภาพล้มเหลว";
            }
        } else {
            echo "กรุณาเลือกไฟล์รูปภาพ";
        }
    } else {
        echo "กรุณาเลือกร้านค้าก่อน";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เพิ่มสินค้า</title>
    <!-- <link rel="stylesheet" href="เพิ่มสินค้า1.css"> -->
    <style>
body {
    font-family: Arial, sans-serif;
    background: linear-gradient(135deg, #f9f9f9, #e0e0e0);
    color: #333;
    margin: 0;
    padding: 0;
}

h2 {
    text-align: center;
    /* margin-top: 20px; */
    color: black;
}

.form {
    display: flex;
    flex-direction: column;
    padding: 20px;
    background: #fff;
}

.form label {
    margin-bottom: 8px;
    font-weight: bold;
    color: black;
}

.form input[type="text"],
.form input[type="number"],
.form input[type="file"],
.form select {
    margin-bottom: 15px;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
    font-size: 14px;
    background-color: #f9f9f9;
}

.form button {
    background-color: #02BF63;
    color: black;
    border: none;
    padding: 12px;
    font-size: 18px;
    border-radius: 15px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.form button:hover {
    background-color: #02BF63;
}

.back-button {
    font-size: 18px;
    display: block;
    text-align: center;
    margin: 20px auto;
    text-decoration: none;
    color: black;
    font-weight: bold;
    background-color: #E83024;
    padding: 12px 20px;
    border-radius: 15px;
    width: 90%;
}

.back-button:hover {
    background-color: #E83024;
}

#popup {
    display: none;
    position: fixed;
    top: 20px;
    left: 50%;
    transform: translateX(-50%);
    background-color: #FFC107;
    color: white;
    padding: 15px 20px;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    font-size: 18px;
    z-index: 1000;
    animation: fadeIn 0.5s ease;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

#popup-overlay {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    z-index: 999;
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

    .container {
        padding-top: 5rem;
         height: 100vh;
         background: #fff;
    }
    </style>
</head>

<body>
    <div class="top-tab"></div>

    <div class="container">
    <h2>เพิ่มสินค้าใหม่</h2>
    <form action="add_product.php" method="POST" enctype="multipart/form-data" class="form">
        <label for="store_id">เลือกร้านค้า:</label>
        <select id="store_id" name="store_id" required>
            <?php
            $conn = new mysqli("localhost", "root", "", "store_management");
            $user_id = $_SESSION['user_id']; // Assuming user_id is stored in session
            $result = $conn->query("SELECT id, name FROM stores WHERE user_id = $user_id");
            while ($row = $result->fetch_assoc()) {
                echo "<option value='" . $row['id'] . "'>" . $row['name'] . "</option>";
            }
            $conn->close();
            ?>
        </select>

        <label for="name">ชื่อสินค้า:</label>
        <input type="text" id="name" name="name" required>

        <label for="price">ราคา (บาท):</label>
        <input type="number" id="price" name="price" step="0.01" required>

        <label for="category">หมวดหมู่:</label>
        <select id="category" name="category">
            <option value="อาหาร">อาหาร</option>
            <option value="เครื่องดื่ม">เครื่องดื่ม</option>
            <option value="ของเล่น">ของเล่น</option>
        </select>

        <label for="extra_option">ตัวเลือกเพิ่มเติม:</label>
        <input type="text" id="extra_option" name="extra_option" placeholder="เช่น ธรรมดา / พิเศษ">

        <label for="extra_price">ค่าใช้จ่ายเพิ่มเติม (บาท):</label>
        <input type="number" id="extra_price" name="extra_price" step="0.01" placeholder="0">

        <label for="image">รูปภาพสินค้า:</label>
        <input type="file" id="image" name="image" accept="image/*" required>

        <button type="submit">บันทึก</button>
        <a href="หน้าแสดงสินค้า.php?store_id=<?php echo isset($_POST['store_id']) ? $_POST['store_id'] : ''; ?>"
        class="back-button">ยกเลิก</a>
        </div>
    </form>

    <!-- Popup message -->
    <div id="popup-overlay"></div>
    <div id="popup">เพิ่มสินค้าเรียบร้อย!</div>

    
    <script>
    // ปิด Popup เมื่อคลิกที่ overlay
    document.getElementById('popup-overlay').onclick = function() {
        document.getElementById('popup').style.display = 'none';
        document.getElementById('popup-overlay').style.display = 'none';
    }
    </script>

</body>

</html>