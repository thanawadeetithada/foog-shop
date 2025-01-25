<?php
session_start();

// ตรวจสอบว่ามีการส่งข้อมูล index มาหรือไม่
if (isset($_POST['index'])) {
    $index = intval($_POST['index']);
    
    // ตรวจสอบว่าตะกร้ามีสินค้าอยู่หรือไม่
    if (isset($_SESSION['cart']) && isset($_SESSION['cart'][$index])) {
        // ลบสินค้าจากตะกร้า
        unset($_SESSION['cart'][$index]);
        
        // รีเซ็ตดัชนีของตะกร้า
        $_SESSION['cart'] = array_values($_SESSION['cart']);
    }
}

// เปลี่ยนเส้นทางกลับไปยังหน้าตะกร้า
header("Location: cart.php");
exit;
?>