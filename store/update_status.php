<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "store_management");

if ($conn->connect_error) {
    die(json_encode(['success' => false, 'message' => 'Connection failed: ' . $conn->connect_error]));
}

// Get the JSON data from the request
$data = json_decode(file_get_contents('php://input'), true);

// Check if the necessary data is provided
if (isset($data['cart_id'], $data['status'])) {
    $cart_id = $data['cart_id'];
    $status = $data['status']; // Assuming 'Completed' is the updated status

    // Update the status in the database
    $stmt = $conn->prepare("UPDATE cart_items SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $status, $cart_id);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Order status updated successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update order status']);
    }

    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid input']);
}

$conn->close();
?>
