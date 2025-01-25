<?php
session_start();
require 'db_connection.php';

$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
$total_price = array_sum(array_column($cart, 'price'));
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/manage_stores.css">
    <title>Cart</title>
</head>
<body>
    <div class="header">
        <input type="text" placeholder="Search">
        <img src="user-icon.png" alt="User">
    </div>

    <div class="cart">
        <h2>Shopping Cart</h2>
        <?php if (empty($cart)): ?>
            <p>Your cart is empty.</p>
        <?php else: ?>
            <ul>
                <?php foreach ($cart as $index => $item): ?>
                    <li>
                        <p><?php echo htmlspecialchars($item['name']); ?></p>
                        <p>Price: <?php echo htmlspecialchars($item['price']); ?> ฿</p>
                        <form action="remove_from_cart.php" method="post">
                            <input type="hidden" name="index" value="<?php echo $index; ?>">
                            <button type="submit">Remove</button>
                        </form>
                    </li>
                <?php endforeach; ?>
            </ul>
            <p>Total Price: <?php echo $total_price; ?> ฿</p>
            <button onclick="location.href='checkout.php'">Proceed to Checkout</button>
        <?php endif; ?>
        <button onclick="location.href='add_to_cart.php'">Add Products</button>
    </div>

    <div class="footer">
        <div>
            <img src="home-icon.png" alt="Home">
            <p>HOME</p>
        </div>
        <div>
            <img src="cart-icon.png" alt="Cart">
            <p>Cart</p>
        </div>
    </div>
</body>
</html>