<?php
session_start();

// Check if the cart is not empty
if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0) {
    // Get the index from the form submission
    $index = isset($_POST['index']) ? $_POST['index'] : null;

    // Remove the item from the cart if index is valid
    if ($index !== null && isset($_SESSION['cart'][$index])) {
        unset($_SESSION['cart'][$index]); // Remove the item
        $_SESSION['cart'] = array_values($_SESSION['cart']); // Re-index the array
    }
}

// Redirect back to the cart page
header("Location: cart.php");
exit;
?>
