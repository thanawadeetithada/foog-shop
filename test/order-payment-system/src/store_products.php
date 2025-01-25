<?php
session_start();
require 'db_connection.php';

// Get store_id from query parameter
$store_id = isset($_GET['store_id']) ? intval($_GET['store_id']) : 0;

// Fetch store name
$store_query = "SELECT name FROM stores WHERE id = ?";
$stmt = $conn->prepare($store_query);
$stmt->bind_param("i", $store_id);
$stmt->execute();
$store_result = $stmt->get_result();
$store = $store_result->fetch_assoc();
$stmt->close();

// Fetch products for the store
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
    <link rel="stylesheet" href="css/store.css">
    <title><?php echo htmlspecialchars($store['name']); ?> - Products</title>
</head>
<body>
    <div class="container">
        <header class="header">
            <div class="search-bar">
                <input type="text" placeholder="ค้นหา">
                <button class="search-button">🔍</button>
            </div>
            <div class="profile-icon">👤</div>
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
                <?php foreach ($products as $product): ?>
                    <div class="menu-item">
                        <img src="<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" onerror="this.onerror=null; this.src='default-image.jpg';">
                        <p><?php echo htmlspecialchars($product['name']); ?></p>
                        <div class="menu-price"><?php echo htmlspecialchars($product['price']); ?> ฿</div>
                        <p>Category: <?php echo htmlspecialchars($product['category']); ?></p>
                        <p>Special Option: <?php echo htmlspecialchars($product['special_option']); ?></p>
                        <p>Extra Option: <?php echo htmlspecialchars($product['extra_option']); ?></p>
                        <p>Extra Price: <?php echo htmlspecialchars($product['extra_price']); ?> ฿</p>
                        <p>Available: <?php echo $product['is_available'] ? 'Yes' : 'No'; ?></p>
                        <form action="add_to_cart.php" method="post">
                            <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                            <input type="hidden" name="store_id" value="<?php echo $store_id; ?>">
                            <button type="submit">เพิ่มตระกร้าสินค้า</button>
                        </form>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
        <footer class="footer">
            <button>🏠 HOME</button>
            <button>🛒</button>
            <button>🔔</button>
            <button>📜</button>
        </footer>
    </div>
</body>
</html>