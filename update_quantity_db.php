<?php
session_start();
include 'db.php'; // นำเข้าไฟล์เชื่อมต่อฐานข้อมูล

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cart_order_item_id'], $_POST['action'])) {
    $cart_order_item_id = intval($_POST['cart_order_item_id']);
    $action = $_POST['action']; // "increase" หรือ "decrease"
    $user_id = $_SESSION['user_id'] ?? null; // ป้องกัน undefined error

    if (!$user_id) {
        echo json_encode(["success" => false, "message" => "Session หมดอายุ"]);
        exit;
    }

    // ตรวจสอบว่าสินค้าเป็นของ user จริงหรือไม่
    $sql_check = "SELECT o.user_id, oi.cart_order_id, oi.quantity, oi.subtotal, oi.product_id 
                  FROM cart_order_items oi 
                  JOIN cart_orders o ON oi.cart_order_id = o.cart_order_id
                  WHERE oi.cart_order_item_id = ? AND o.user_id = ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param("ii", $cart_order_item_id, $user_id);
    $stmt_check->execute();
    $result = $stmt_check->get_result();

    if ($result->num_rows > 0) {
        $data = $result->fetch_assoc();
        $cart_order_id = $data['cart_order_id'];
        $quantity = $data['quantity'];
        $product_id = $data['product_id'];

        // ดึงราคาต่อหน่วยของสินค้า
        $sql_price = "SELECT price FROM products WHERE product_id = ?";
        $stmt_price = $conn->prepare($sql_price);
        $stmt_price->bind_param("i", $product_id);
        $stmt_price->execute();
        $stmt_price->bind_result($unit_price);
        $stmt_price->fetch();
        $stmt_price->close();

        // คำนวณค่าใหม่
        $new_quantity = ($action === "increase") ? ($quantity + 1) : max(1, $quantity - 1);
        $new_subtotal = $new_quantity * $unit_price;

        // อัปเดตค่าใหม่ในฐานข้อมูล
        $sql_update = "UPDATE cart_order_items SET quantity = ?, subtotal = ? WHERE cart_order_item_id = ?";
        $stmt_update = $conn->prepare($sql_update);
        $stmt_update->bind_param("idi", $new_quantity, $new_subtotal, $cart_order_item_id);

        if ($stmt_update->execute()) {
            // อัปเดตราคารวมของออเดอร์
            $sql_total = "SELECT SUM(subtotal) FROM cart_order_items WHERE cart_order_id = ?";
            $stmt_total = $conn->prepare($sql_total);
            $stmt_total->bind_param("i", $cart_order_id);
            $stmt_total->execute();
            $stmt_total->bind_result($new_total_price);
            $stmt_total->fetch();
            $stmt_total->close();

            // อัปเดต total_price ใน orders
            $sql_update_order = "UPDATE cart_orders SET total_price = ? WHERE cart_order_id = ?";
            $stmt_update_order = $conn->prepare($sql_update_order);
            $stmt_update_order->bind_param("di", $new_total_price, $cart_order_id);
            $stmt_update_order->execute();
            $stmt_update_order->close();

            // ส่งข้อมูลกลับ
            echo json_encode([
                "success" => true, 
                "new_quantity" => $new_quantity, 
                "new_subtotal" => number_format($new_subtotal, 2), 
                "new_total_price" => number_format($new_total_price, 2)
            ]);
        } else {
            echo json_encode(["success" => false, "message" => "อัปเดตจำนวนสินค้าไม่สำเร็จ"]);
        }
    } else {
        echo json_encode(["success" => false, "message" => "ไม่พบสินค้านี้"]);
    }

    exit;
}

echo json_encode(["success" => false, "message" => "คำขอไม่ถูกต้อง"]);
exit;
?>
