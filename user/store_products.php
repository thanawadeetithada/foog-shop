<?php
session_start();

// ข้อมูลการเชื่อมต่อฐานข้อมูล
$host = "localhost";
$username = "root";
$password = "";
$dbname = "store_management";

// สร้างการเชื่อมต่อ
$conn = new mysqli($host, $username, $password, $dbname);

// ตรวจสอบการเชื่อมต่อ
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// ตรวจสอบว่าผู้ใช้ล็อกอินหรือไม่
if (!isset($_SESSION['user_id'])) {
    echo "Please log in to add items to the cart.";
    exit;
}

// จัดการการเพิ่มสินค้าลงในตะกร้า
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id']; // รับ user_id จาก session
    $product_id = $_POST['product_id'];
    $store_id = $_POST['store_id'];
    $quantity = $_POST['quantity'];
    $special_option = isset($_POST['special_option']) ? 1 : 0; // 1 ถ้ามีตัวเลือกพิเศษ
    $note = $_POST['note'];
    $base_price = $_POST['base_price']; // ราคาเริ่มต้นจากสินค้า
    $total_price = $base_price * $quantity;

    // เพิ่มราคาเมื่อเลือกตัวเลือกพิเศษ
    if ($special_option) {
        $total_price += 10 * $quantity; // เพิ่ม 10 บาทต่อชิ้น
    }

    // เพิ่มสินค้าในตะกร้าลงฐานข้อมูล
    $query = "INSERT INTO cart_items (user_id, product_id, store_id, quantity, special_option, note, total_price) 
              VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("iiisssd", $user_id, $product_id, $store_id, $quantity, $special_option, $note, $total_price);
    $stmt->execute();
    $stmt->close();

    // รีไดเรกต์ไปยังหน้าตะกร้า
    header("Location: cart.php");
    exit;
}

// รับ store_id จากพารามิเตอร์ใน URL
$store_id = isset($_GET['store_id']) ? intval($_GET['store_id']) : 0;

if ($store_id <= 0) {
    echo "Invalid store ID.";
    exit;
}

// ดึงข้อมูลชื่อร้านค้า
$store_query = "SELECT name FROM stores WHERE id = ?";
$stmt = $conn->prepare($store_query);
$stmt->bind_param("i", $store_id);
$stmt->execute();
$store_result = $stmt->get_result();
$store = $store_result->fetch_assoc();
$stmt->close();

if (!$store) {
    echo "Store not found.";
    exit;
}

// ดึงข้อมูลสินค้าจากร้านค้า
$product_query = "SELECT * FROM products WHERE store_id = ?";
$stmt = $conn->prepare($product_query);
$stmt->bind_param("i", $store_id);
$stmt->execute();
$product_result = $stmt->get_result();
$products = $product_result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="store.css">
    <title><?php echo htmlspecialchars($store['name']); ?> - Products</title>
</head>
<body>
    <div class="container">
        <header class="header">
            <div class="search-bar">
                <input type="text" placeholder="ค้นหา">
                <button class="search-button">🔍</button>
            </div>
        </header>
        <nav class="categories">
            <button>อาหาร</button>
            <button>เครื่องดื่ม</button>
            <button>ของทานเล่น</button>
            <button>อื่นๆ</button>
        </nav>
        <section class="menu-section">
            <h2><?php echo htmlspecialchars($store['name']); ?></h2>
            <div class="menu-grid">
                <?php if (count($products) > 0): ?>
                    <?php foreach ($products as $product): ?>
                        <div class="menu-item">
                            <img class="menu-image" 
                                 src="<?php echo htmlspecialchars($product['image'] ?: 'images/default-image.jpg'); ?>" 
                                 alt="<?php echo htmlspecialchars($product['name']); ?>" 
                                 onerror="this.onerror=null; this.src='images/ไก่ต้ม.jpg';">
                            <div class="menu-details">
                                <h3><?php echo htmlspecialchars($product['name']); ?></h3>
                                <p class="menu-price" id="price_<?php echo $product['id']; ?>"><?php echo htmlspecialchars($product['price']); ?> ฿</p>
                                 
                                <form action="store_products.php" method="post" class="add-to-cart-form">
                                    <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                                    <input type="hidden" name="store_id" value="<?php echo $store_id; ?>">
                                    <input type="hidden" name="base_price" value="<?php echo $product['price']; ?>">
                                    
                                    <div class="special-options">
                                        <label>
                                            <input type="checkbox" name="special_option" value="1" onchange="updatePrice(this, <?php echo $product['price']; ?>, <?php echo $product['id']; ?>)" data-base-price="<?php echo $product['price']; ?>" data-product-id="<?php echo $product['id']; ?>"> ตัวเลือกพิเศษ +10 บาท
                                        </label>
                                    </div>

                                    <textarea name="note" placeholder="หมายเหตุ (ถ้ามี)" rows="2" class="note-box"></textarea>

                                    <div class="quantity-selector">
                                        <button type="button" class="decrease-btn">-</button>
                                        <input type="number" id="quantity_<?php echo $product['id']; ?>" name="quantity" value="1" min="1">
                                        <button type="button" class="increase-btn">+</button>
                                    </div>

                                    <button type="submit" class="add-to-cart-btn" id="add_to_cart_<?php echo $product['id']; ?>">
                                        เพิ่มไปยังตะกร้า - <?php echo htmlspecialchars($product['price']); ?> ฿
                                    </button>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>ไม่มีสินค้าที่ร้านนี้</p>
                <?php endif; ?>
            </div>
        </section>
        <footer class="footer">
            <button onclick="location.href='manage_stores.php'">🏠 HOME</button>
            <button onclick="location.href='cart.php'">🛒</button> 
            <button onclick="location.href='แจ้งเตือนสถานะ.php'">🔔</button>
            <button onclick="location.href='แจ้งเตือนสถานะ.php'">📜</button>
        </footer>
    </div>

    <script>
        function updatePrice(checkbox, basePrice, productId) {
            const button = document.getElementById(`add_to_cart_${productId}`);
            const quantityInput = document.getElementById(`quantity_${productId}`);
            const quantity = parseInt(quantityInput.value) || 1;

            let totalPrice = basePrice * quantity;

            if (checkbox.checked) {
                totalPrice += 10 * quantity; // เพิ่ม 10 บาทต่อจำนวน
            }

            button.textContent = `เพิ่มไปยังตะกร้า - ${totalPrice} ฿`;
        }

        document.addEventListener("DOMContentLoaded", function () {
            document.querySelectorAll(".quantity-selector").forEach(function (selector) {
                const decreaseBtn = selector.querySelector(".decrease-btn");
                const increaseBtn = selector.querySelector(".increase-btn");
                const quantityInput = selector.querySelector("input[type='number']");

                decreaseBtn.addEventListener("click", function () {
                    let currentValue = parseInt(quantityInput.value) || 1;
                    if (currentValue > 1) {
                        quantityInput.value = currentValue - 1;
                        quantityInput.dispatchEvent(new Event('change'));
                    }
                });

                increaseBtn.addEventListener("click", function () {
                    let currentValue = parseInt(quantityInput.value) || 1;
                    quantityInput.value = currentValue + 1;
                    quantityInput.dispatchEvent(new Event('change'));
                });

                quantityInput.addEventListener("change", function () {
                    const checkbox = selector.closest(".menu-item").querySelector("input[type='checkbox']");
                    updatePrice(checkbox, parseInt(checkbox.dataset.basePrice), parseInt(checkbox.dataset.productId));
                });
            });
        });
    </script>
</body>
</html>
