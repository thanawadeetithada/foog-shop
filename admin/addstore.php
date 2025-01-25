<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เพิ่มร้านค้า</title>
    <link rel="stylesheet" href="addstroes.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 90%;
            max-width: 800px;
            margin: 20px auto;
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .search-bar {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .search-bar input {
            flex: 1;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .search-bar button {
            padding: 10px 20px;
            margin-left: 10px;
            border: none;
            background-color: #4caf50;
            color: #fff;
            border-radius: 5px;
            cursor: pointer;
        }

        .search-bar button:hover {
            background-color: #45a049;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }

        .shop-list {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }

        .shop-card {
            border: 1px solid #ddd;
            border-radius: 10px;
            padding: 10px;
            text-align: center;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .shop-card img {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
            margin-bottom: 10px;
        }

        .shop-card h3 {
            margin: 10px 0;
            color: #555;
        }

        .actions {
            margin-top: 10px;
        }

        .actions button {
            padding: 8px 15px;
            margin: 5px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .actions .view-btn {
            background-color: #007bff;
            color: #fff;
        }

        .actions .delete-btn {
            background-color: #e74c3c;
            color: #fff;
        }

        .add-shop {
            text-align: center;
            margin-top: 20px;
        }

        .add-shop button {
            padding: 10px 20px;
            background-color: #4caf50;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .add-shop button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="search-bar">
            <input type="text" placeholder="ค้นหา...">
            <button>ค้นหา</button>
        </div>

        <h2>ร้านค้า</h2>
        <div class="shop-list">
            <?php
            // เชื่อมต่อฐานข้อมูล
            $conn = new mysqli('localhost', 'root', '', 'store_management');

            // ตรวจสอบการเชื่อมต่อ
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // ลบร้านค้า
            if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_id'])) {
                $delete_id = $_POST['delete_id'];

                // ลบสินค้าที่เกี่ยวข้องก่อน
                $delete_products_sql = "DELETE FROM products WHERE store_id = ?";
                $stmt = $conn->prepare($delete_products_sql);
                $stmt->bind_param("i", $delete_id);
                $stmt->execute();
                $stmt->close();

                // ลบร้านค้า
                $delete_store_sql = "DELETE FROM stores WHERE id = ?";
                $stmt = $conn->prepare($delete_store_sql);
                $stmt->bind_param("i", $delete_id);
                $stmt->execute();
                $stmt->close();
            }

            // ดึงข้อมูลร้านค้า
            $sql = "SELECT * FROM stores";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                // แสดงข้อมูลร้านค้า
                while ($row = $result->fetch_assoc()) {
                    // ตรวจสอบการมีอยู่ของไฟล์รูปภาพ
                    $image = !empty($row["image"]) ? $row["image"] : 'default.jpg'; // ใช้ default.jpg หากไม่มีรูป
                    $imagePath = 'uploads/' . htmlspecialchars($image); // ตรวจสอบเส้นทาง
                    if (!file_exists($imagePath)) {
                        $imagePath = 'uploads/default.jpg'; // ใช้ default หากไม่พบไฟล์
                    }

                    echo '<div class="shop-card">';
                    // echo '<img src="' . $imagePath . '" alt="' . htmlspecialchars($row["name"]) . '">';
                    echo '<h3>' . htmlspecialchars($row["name"]) . '</h3>';
                    echo '<div class="actions">';
                    echo '<form action="home.php" method="get" style="display:inline;">';
                    echo '<input type="hidden" name="store_id" value="' . $row["id"] . '">';
                    echo '<button type="submit" class="view-btn">ดูร้านค้า</button>';
                    echo '</form>';
                    echo '<form action="" method="post" style="display:inline;">';
                    echo '<input type="hidden" name="delete_id" value="' . $row["id"] . '">';
                    echo '<button type="submit" class="delete-btn">ลบ</button>';
                    echo '</form>';
                    echo '</div>';
                    echo '</div>';
                }
            } else {
                echo "<p>ไม่มีร้านค้าในระบบ</p>";
            }

            $conn->close();
            ?>
        </div>
        <div class="add-shop">
            <form action="add_shop.php" method="get">
                <button type="submit">เพิ่มร้านค้า</button>
            </form>
        </div>
    </div>
</body>
</html>
