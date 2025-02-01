<?php
include 'db.php';

// รับค่าหมวดหมู่จาก GET
$category = isset($_GET['category']) ? $_GET['category'] : '';

// สร้าง SQL query เพื่อกรองร้านค้าตามหมวดหมู่
if ($category) {
    // ถ้ามีการกรองหมวดหมู่
    $sql = "SELECT store_id, store_name, user_name, category, phone, image_url FROM stores WHERE category = ?";
} else {
    // ถ้าไม่มีการกรองหมวดหมู่
    $sql = "SELECT store_id, store_name, user_name, category, phone, image_url FROM stores";
}

$stmt = $conn->prepare($sql);
if ($category) {
    $stmt->bind_param("s", $category); // กรณีที่มีการกรองหมวดหมู่
}
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>RMUTP Food</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/js/all.min.js" crossorigin="anonymous">
    </script>

    <style>
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
        background-color: #fff;
    }

    .top-tab {
        background-color: #FFDE59;
        padding: 10px;
        display: flex;
        justify-content: center;
        align-items: center;
        text-align: center;
    }

    .top-tab form {
        margin: 0 10px 0 0px;
        align-items: center;
        justify-content: center;
        width: 80%;
    }

    .top-tab input {
        border: none;
        padding: 10px;
        border-radius: 20px;
        width: 70%;
        font-size: 14px;
    }

    .banner {
        display: flex;
        justify-content: center;
        align-items: center;
        margin: 10px 20px;
    }

    .banner-img {
        width: 100%;
        max-height: 200px;
        object-fit: contain;
        border-radius: 10px;
    }

    .categories {
        display: flex;
        justify-content: space-around;
        padding: 10px;
        background-color: white;
    }

    .category button {
        background: #FFDE59;
        border: none;
        padding: 15px 20px;
        border-radius: 10px;
        font-size: 1.5em;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 80px;
        height: 80px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }


    .category p {
        margin-top: 8px;
        font-size: 14px;
        color: #333;
        font-weight: bold;
    }

    .categories button svg {
        font-size: 2rem;
        color: white;
    }

    .category {
        text-align: center;
    }

    .recommended {
        margin: 0px 20px 20px 20px;
    }

    .recommended h3 {
        margin-bottom: 10px;
        margin-top: 0px;
        font-size: 18px;
        color: #333;
    }

    .recommended .shops {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 15px;
        justify-content: center;
    }

    .shop {
        text-align: center;
        background: #f9f9f9;
        padding: 10px 10px 25px;
        border-radius: 10px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        max-width: 100%;
    }

    .shop img {
        width: 100%;
        max-width: 250px;
        height: 100%;
        max-height: 100px;
        border-radius: 10px;
        margin-bottom: 20px;
    }

    .shop a {
        text-decoration: none;
        color: black;
        margin-bottom: 20px !important;
    }

    /* Footer Section */
    .footer {
        align-items: center;
        display: flex;
        justify-content: space-around;
        background-color: #fff;
        padding: 5px 0;
        margin-left: 20px;
        position: fixed;
        bottom: 0;
        margin-bottom: 20px;
        width: 90%;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        border-radius: 100px;
    }

    .footer-item {
        text-align: center;
        color: #FFDE59;
        font-size: 1.5rem;
        position: relative;
        cursor: pointer;
    }

    .footer-item p {
        font-size: 0.9rem;
        font-weight: bold;
        margin: 5px 0 0;
    }

    .footer-item.active {
        background-color: #FFDE59;
        border-radius: 100px;
        padding: 10px 20px;
        color: #fff;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        display: flex;
        align-items: center;
    }

    .notification-badge {
        position: absolute;
        top: -5px;
        right: -5px;
        width: 10px;
        height: 10px;
        background-color: red;
        border-radius: 50%;
    }

    .search-form {
        width: 100%;
        max-width: 500px;
        position: relative;
    }

    .search-box {
        display: flex;
        align-items: center;
        position: relative;
        border-radius: 20px;
        background-color: #fff;
        border: 1px solid #ccc;
        overflow: hidden;
    }

    .search-box input {
        flex: 1;
        border: none;
        padding: 10px 15px;
        border-radius: 20px;
        font-size: 14px;
        outline: none;
    }

    .search-box button {
        border: none;
        background: none;
        cursor: pointer;
        padding: 10px 15px;
        color: #555;
    }

    .fa-circle-user {
        font-size: 1.8rem;
        color: #ffffff;
        background-color: #ccc;
        border-radius: 15px;
    }
    </style>
</head>

<body>
    <div class="top-tab">
        <form method="GET" action="search.php" class="search-form">
            <div class="search-box">
                <input type="text" name="query" placeholder="ค้นหาสินค้า"
                    value="<?php echo isset($_GET['query']) ? htmlspecialchars($_GET['query']) : ''; ?>">
                <button type="submit"><i class="fas fa-search"></i></button>
            </div>
        </form>
        <a href="logout.php">
            <i class="fa-solid fa-circle-user"></i>
        </a>
    </div>

    <div class="banner">
        <img src="img/RMUTPFOOD.jpg" alt="RMUTP Food" class="banner-img">
    </div>

    <nav class="categories">
        <div class="category">
            <a href="user_main.php?category=อาหาร"><button><i class="fa-solid fa-utensils"></i></button></a>
            <p>อาหาร</p>
        </div>
        <div class="category">
            <a href="user_main.php?category=เครื่องดื่ม"><button><i class="fa-solid fa-mug-hot"></i></button></a>
            <p>เครื่องดื่ม</p>
        </div>
        <div class="category">
            <a href="user_main.php?category=ของทานเล่น"><button><i class="fa-solid fa-ice-cream"></i></button></a>
            <p>ของทานเล่น</p>
        </div>
        <div class="category">
            <a href="user_main.php?category=อื่นๆ"><button><i class="fa-solid fa-table-cells-large"></i></button></a>
            <p>อื่นๆ</p>
        </div>
    </nav>


    <div class="recommended">
        <h3>ร้านค้าทั้งหมด</h3>
        <div class="shops">
            <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $image_url = htmlspecialchars($row['image_url']); 
                $store_name = htmlspecialchars($row['store_name']);
                $store_id = htmlspecialchars($row['store_id']);

                echo '<div class="shop" onclick="location.href=\'user_detail_shop.php?store_id=' . $store_id . '\'">
                <img src="' . $image_url . '" alt="' . $store_name . '">
                <a href="user_detail_shop.php?store_id=' . $store_id . '">' . $store_name . '</a>
            </div>';
            }
        } else {
            echo "<p>ไม่มีร้านค้า</p>";
        }
        ?>
        </div>
    </div>

    <footer class="footer">
        <div class="footer-item active" onclick="window.location.href='user_main.php'">
            <i class="fa-solid fa-house-chimney"></i>&nbsp;
            <p>HOME</p>
        </div>
        <div class="footer-item" onclick="window.location.href='user_order.php'">
            <i class="fa-solid fa-file-alt"></i>
        </div>
        <div class="footer-item" onclick="window.location.href='user_cart.php'">
            <i class="fa-solid fa-cart-shopping"></i>
        </div>
        <div class="footer-item notification" onclick="window.location.href='user_notification.php'">
            <i class="fa-solid fa-bell"></i>
            <span class="notification-badge"></span>
        </div>
    </footer>

</body>

</html>