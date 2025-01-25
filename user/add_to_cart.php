<?php
session_start();
require 'db_connection.php';

// Ensure user_id is set in session
if (!isset($_SESSION['user_id'])) {
    die('User not logged in.');
}

// Check if the user has an active session (cart)
if (!isset($_SESSION['order_id'])) {
    // If there's no active order, create a new order
    $order_query = "INSERT INTO orders (user_id, status) VALUES (?, 'pending')";
    $stmt = $conn->prepare($order_query);
    if ($stmt === false) {
        die('Error preparing order query: ' . $conn->error);
    }

    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
    $order_id = $stmt->insert_id; // Get the new order ID
    $_SESSION['order_id'] = $order_id; // Store the order ID in the session
    $stmt->close();
} else {
    // If there's already an active order, use the existing order_id from session
    $order_id = $_SESSION['order_id'];
}

// Get product details from the form
$product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
$quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;

// Insert the product into the order_items table
$insert_query = "INSERT INTO order_items (order_id, product_id, quantity) VALUES (?, ?, ?)";
$stmt = $conn->prepare($insert_query);
if ($stmt === false) {
    die('Error preparing insert query: ' . $conn->error);
}

$stmt->bind_param("iii", $order_id, $product_id, $quantity);
$stmt->execute();
$stmt->close();

// Fetch product details to store in the session cart
$product_query = "SELECT * FROM products WHERE id = ?";
$stmt = $conn->prepare($product_query);
$stmt->bind_param("i", $product_id);
$stmt->execute();
$product_result = $stmt->get_result();
$product = $product_result->fetch_assoc();
$stmt->close();

$product['quantity'] = $quantity;
$product['total_price'] = $product['price'] * $quantity;

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$_SESSION['cart'][] = $product;

// Redirect to the cart page after adding to cart
header("Location: cart.php");
exit;
?>