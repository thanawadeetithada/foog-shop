<?php
session_start();
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["product_id"], $_POST["is_show"])) {
    $product_id = intval($_POST["product_id"]);
    $is_show = intval($_POST["is_show"]);

    if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'store_owner' || !isset($_SESSION['store_id'])) {
        echo json_encode(["status" => "error", "message" => "Unauthorized"]);
        exit();
    }

    $store_id = $_SESSION['store_id'];

    $sql = "UPDATE products SET is_show = ? WHERE product_id = ? AND store_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iii", $is_show, $product_id, $store_id);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Updated successfully"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Update failed"]);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request"]);
}
?>
