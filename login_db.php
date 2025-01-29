<?php
session_start();
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $phone = $_POST['phone'];
    $password = $_POST['password'];

    // ดึงข้อมูลจากตาราง users และ stores โดยใช้ INNER JOIN
    $sql = "SELECT u.user_id, u.phone, u.password, u.role, s.store_id
            FROM users u
            LEFT JOIN stores s ON u.user_id = s.owner_id
            WHERE u.phone = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $phone);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        
        if (password_verify($password, $user['password'])) {
            // เก็บข้อมูลใน session
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['phone'] = $user['phone'];
            $_SESSION['role'] = $user['role'];

            // ถ้า role เป็น store_owner ก็เก็บ store_id
            if ($user['role'] == 'store_owner') {
                $_SESSION['store_id'] = $user['store_id']; // เก็บ store_id
            }

            // รีไดเร็กไปยังหน้าต่างๆ ตามบทบาท
            if ($user['role'] == "customer") {
                header("Location: user_main.php");
                exit();
            } elseif ($user['role'] == "admin") {
                header("Location: admin_main.php");
                exit();
            } elseif ($user['role'] == "store_owner") {
                header("Location: shop_main.php");
                exit();
            }
        } else {
            echo "<script>alert('รหัสผ่านไม่ถูกต้อง!'); window.location.href = 'index.php';</script>";
        }
    } else {
        echo "<script>alert('ไม่พบเบอร์โทรนี้ในระบบ!'); window.location.href = 'index.php';</script>";
    }

    $stmt->close();
    $conn->close();
}
?>
