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

$search = ''; // ตัวแปรเก็บคำค้นหา

// ตรวจสอบการส่งคำค้นหามาจากฟอร์ม
if (isset($_POST['search'])) {
    $search = $_POST['search'];
}

// ดึงข้อมูลร้านค้าของผู้ใช้ที่เข้าสู่ระบบ
$user_id = $_SESSION['user_id'];
$query = "SELECT id FROM stores WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$store = $result->fetch_assoc();

if ($store) {
    $store_id = $store['id'];

    // สร้างคำสั่ง SQL เพื่อค้นหาสินค้า
    $query = "SELECT * FROM products WHERE store_id = ? AND name LIKE ?";
    $stmt = $conn->prepare($query);
    $search_param = "%" . $search . "%"; // ทำให้การค้นหาตรงกับคำบางส่วนได้
    $stmt->bind_param("is", $store_id, $search_param);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $result = null;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>สินค้า</title>
    <link rel="stylesheet" href="แสดงสินค้าา.css">
    <script src="script.js" defer></script>
    <script>
        function showSuccessMessage(message) {
            const messageBox = document.createElement("div");
            messageBox.textContent = message;
            messageBox.style.position = "fixed";
            messageBox.style.top = "10%";
            messageBox.style.left = "50%";
            messageBox.style.transform = "translateX(-50%)";
            messageBox.style.padding = "20px 30px";
            messageBox.style.backgroundColor = "#4CAF50";
            messageBox.style.color = "white";
            messageBox.style.borderRadius = "10px";
            messageBox.style.boxShadow = "0 4px 8px rgba(0, 0, 0, 0.2)";
            messageBox.style.textAlign = "center";
            messageBox.style.fontSize = "18px";
            messageBox.style.zIndex = "1000";
            document.body.appendChild(messageBox);

            setTimeout(() => {
                messageBox.remove();
            }, 3000);
        }

        // ฟังก์ชันคำนวณราคาใหม่เมื่อเลือกตัวเลือก
        function updatePrice(originalPrice, productId) {
            const priceField = document.getElementById('price-' + productId); // ใช้ productId แทน
            const specialOption = document.querySelector('input[name="special_option_' + productId + '"]:checked').value;

            if (specialOption === 'special') {
                priceField.value = (originalPrice + 10).toFixed(2); // เพิ่ม 10 บาท
            } else {
                priceField.value = originalPrice.toFixed(2); // คืนค่าราคาเดิม
            }
        }

        function showEditForm(productId) {
            var modal = document.getElementById('editModal-' + productId);
            modal.style.display = "block";
        }

        function closeEditForm(productId) {
            var modal = document.getElementById('editModal-' + productId);
            modal.style.display = "none";
        }

        // เมื่อคลิกนอก Modal ให้ปิด Modal
        window.onclick = function(event) {
            var modals = document.getElementsByClassName("modal");
            for (var i = 0; i < modals.length; i++) {
                if (event.target == modals[i]) {
                    modals[i].style.display = "none";
                }
            }
        }

        // ฟังก์ชันเปลี่ยนสถานะสินค้า
        function toggleStatus(productId) {
            const checkbox = document.querySelector(`.toggle-status[data-id='${productId}']`);
            const isAvailable = checkbox.checked ? 1 : 0;
            const soldOutLabel = document.querySelector(`.sold-out[data-id='${productId}']`);

            const xhr = new XMLHttpRequest();
            xhr.open("POST", "update_status.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    showSuccessMessage("สถานะเปลี่ยนแล้ว");
                    if (isAvailable) {
                        if (soldOutLabel) {
                            soldOutLabel.remove();
                        }
                    } else {
                        if (!soldOutLabel) {
                            const newSoldOutLabel = document.createElement("div");
                            newSoldOutLabel.className = "sold-out";
                            newSoldOutLabel.setAttribute("data-id", productId);
                            newSoldOutLabel.textContent = "SOLD OUT";
                            checkbox.closest(".product-display").appendChild(newSoldOutLabel);
                        }
                    }
                }
            };
            xhr.send(`id=${productId}&is_available=${isAvailable}`);
        }
    </script>
</head>
<body>
<?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            showSuccessMessage("ดำเนินการสำเร็จ!");
        });
    </script>
<?php endif; ?>
<div class="header">
    <form method="POST" action="">
        <input type="text" name="search" placeholder="ค้นหา" value="<?php echo htmlspecialchars($search); ?>">
        <button type="submit">ค้นหา</button>
    </form>
</div>
<h2>สินค้าทั้งหมด</h2>
<div class="product-grid">
    <?php if ($result): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="product-card">
                <div class="product-display">
                    <img src="uploads/<?php echo $row['image']; ?>" alt="<?php echo htmlspecialchars($row['name']); ?>">
                    <div class="product-info">
                        <h3><?php echo htmlspecialchars($row['name']); ?></h3>
                        <span class="price"><?php echo number_format($row['price'], 2); ?>฿</span>
                        <label class="switch">
                            <input type="checkbox" class="toggle-status" data-id="<?php echo $row['id']; ?>" <?php echo $row['is_available'] ? 'checked' : ''; ?> onclick="toggleStatus(<?php echo $row['id']; ?>)">
                            <span class="slider round"></span>
                        </label>
                    </div>
                    <?php if (!$row['is_available']): ?>
                        <div class="sold-out" data-id="<?php echo $row['id']; ?>">SOLD OUT</div>
                    <?php endif; ?>
                    <button type="button" class="edit-btn" onclick="showEditForm(<?php echo $row['id']; ?>)">แก้ไข</button>
                    <!-- ปุ่มลบสินค้า -->
                    <form action="delete_product.php" method="POST" class="delete-form">
                        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                        <button type="submit" class="delete-button">ลบ</button>
                    </form>
                </div>

                <!-- ฟอร์มแก้ไขสินค้า Modal -->
                <div id="editModal-<?php echo $row['id']; ?>" class="modal">
                    <div class="modal-content">
                        <span class="close-btn" onclick="closeEditForm(<?php echo $row['id']; ?>)">&times;</span>

                        <!-- ฟอร์มแก้ไขสินค้า -->
                        <form action="edit_product.php" method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">

                            <!-- ชื่อสินค้า -->
                            <label for="name-<?php echo $row['id']; ?>">ชื่อสินค้า:</label>
                            <input type="text" id="name-<?php echo $row['id']; ?>" name="name" value="<?php echo htmlspecialchars($row['name']); ?>" required>

                            <!-- ราคา -->
                            <label for="price-<?php echo $row['id']; ?>">ราคา (บาท):</label>
                            <input type="number" id="price-<?php echo $row['id']; ?>" name="price" value="<?php echo $row['price']; ?>" step="0.01" required>

                            <!-- หมวดหมู่ -->
                            <label for="category-<?php echo $row['id']; ?>">หมวดหมู่:</label>
                            <select id="category-<?php echo $row['id']; ?>" name="category" required>
                                <option value="อาหาร" <?php echo ($row['category'] == 'อาหาร') ? 'selected' : ''; ?>>อาหาร</option>
                                <option value="เครื่องดื่ม" <?php echo ($row['category'] == 'เครื่องดื่ม') ? 'selected' : ''; ?>>เครื่องดื่ม</option>
                                <option value="ของทานเล่น" <?php echo ($row['category'] == 'ของทานเล่น') ? 'selected' : ''; ?>>ของทานเล่น</option>
                            </select>

                            <!-- ตัวเลือกพิเศษ/ปกติ -->
                            <label for="special-option-<?php echo $row['id']; ?>">ตัวเลือก:</label>
                            <div class="special-option-group">
                                <label>
                                    <input type="radio" name="special_option" value="normal" <?php echo ($row['special_option'] == 'normal') ? 'checked' : ''; ?>>
                                    ปกติ
                                </label>
                                <label>
                                    <input type="radio" name="special_option" value="special" <?php echo ($row['special_option'] == 'special') ? 'checked' : ''; ?>>
                                    พิเศษ (+10 บาท)
                                </label>
                            </div>

                            <!-- รูปภาพสินค้า -->
                            <label for="image-<?php echo $row['id']; ?>">รูปภาพสินค้า:</label>
                            <input type="file" id="image-<?php echo $row['id']; ?>" name="image" accept="image/*">

                            <!-- ปุ่มบันทึก -->
                            <button type="submit" class="submit-btn">บันทึก</button>
                            <button type="button" class="cancel-btn" onclick="closeEditForm(<?php echo $row['id']; ?>)">ยกเลิก</button>
                        </form>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>ไม่พบสินค้า</p>
    <?php endif; ?>
</div>
<div class="add-product-container">
    <a href="add_product.php" class="add-product">เพิ่มสินค้าใหม่</a>
</div>
</body>
</html>