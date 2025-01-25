<?php
session_start();
require 'db_connection.php';

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// เชื่อมต่อฐานข้อมูล
$conn = new mysqli("localhost", "root", "", "store_management");

// ตรวจสอบการเชื่อมต่อฐานข้อมูล
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// รับค่า store_id จาก query parameter
$store_id = isset($_GET['store_id']) ? intval($_GET['store_id']) : 0;

// ดึงข้อมูลร้านค้าจาก store_details โดยใช้ store_id
$query = "SELECT shop_name, owner_name FROM store_details WHERE store_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $store_id);
$stmt->execute();
$result = $stmt->get_result();
$store_details = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $shop_name = $_POST['shop_name'];
    $owner_name = $_POST['owner_name'];

    // อัปเดตข้อมูลร้านค้าใน store_details
    $update_query = "UPDATE store_details SET shop_name = ?, owner_name = ? WHERE store_id = ?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("ssi", $shop_name, $owner_name, $store_id);
    if ($stmt->execute()) {
        header("Location: home.php?store_id=$store_id&success=1");
        exit();
    } else {
        echo "เกิดข้อผิดพลาด: " . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>แก้ไขข้อมูลร้านค้า</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f7fc;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 100%;
            max-width: 600px;
            margin: 50px auto;
            padding: 30px;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            color: #007bff;
            font-size: 24px;
            margin-bottom: 30px;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        form label {
            margin-bottom: 8px;
            font-size: 16px;
            color: #555;
        }

        form input {
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
            background-color: #f8f9fa;
        }

        form input:focus {
            border-color: #007bff;
            outline: none;
        }

        button {
            padding: 12px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #218838;
        }

        .back-button {
            text-align: center;
            display: block;
            margin-top: 20px;
            font-size: 16px;
            color: #007bff;
            text-decoration: none;
            transition: color 0.3s;
        }

        .back-button:hover {
            color: #0056b3;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>แก้ไขข้อมูลร้านค้า</h2>
    <form action="edit_store.php?store_id=<?php echo $store_id; ?>" method="POST">
        <label for="shop_name">ชื่อร้าน:</label>
        <input type="text" id="shop_name" name="shop_name" value="<?php echo htmlspecialchars($store_details['shop_name']); ?>" required>

        <label for="owner_name">ชื่อเจ้าของร้าน:</label>
        <input type="text" id="owner_name" name="owner_name" value="<?php echo htmlspecialchars($store_details['owner_name']); ?>" required>

        <button type="submit">บันทึกการเปลี่ยนแปลง</button>
    </form>
    <a href="addstore.php?store_id=<?php echo $store_id; ?>" class="back-button">กลับไปหน้าหลัก</a>
</div>

</body>
</html>
