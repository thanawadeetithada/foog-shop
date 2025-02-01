<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
    header('Location: index.php'); 
    exit();
}

if (!isset($_GET['store_id'])) {
    header('Location: index.php');
    exit();
}

$store_id = $_GET['store_id']; 

$sql = "SELECT product_id, product_name, price, image_url, is_show FROM products WHERE store_id = ? AND is_show = 1";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $store_id);
$stmt->execute();
$result = $stmt->get_result();

$products = []; 
while ($row = $result->fetch_assoc()) {
    $products[] = $row;
}
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
        height: 100vh;
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

    .recommended {
        margin: 20px;
    }

    .recommended h3 {
        margin-bottom: 10px;
        margin-top: 15px;
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
        padding: 10px;
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
    }

    .menu-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-left: 5px;
        margin-right: 5px;
    }

    .price {
        margin-left: auto;
        color: #333;
        font-weight: bold;
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
    </div>

    <div class="recommended">
        <h3>เมนูยอดนิยม</h3>
        <div class="shops">
            <?php
        if (!empty($products)) {
            foreach ($products as $row) {
                $image_url = htmlspecialchars($row['image_url']);
                $product_name = htmlspecialchars($row['product_name']);
                $price = number_format($row['price'], 2);

                echo '<a href="user_menu.php?product_id=' . $row['product_id'] . '" style="text-decoration: none; color: inherit;">
    <div class="shop">
        <img src="' . $image_url . '" alt="' . $product_name . '">
        <div class="menu-item">
            <span>' . $product_name . '</span>
            <p>' . $price . '฿</p>
        </div>
    </div>
</a>'; 
            }
        } else {
            echo "<p>ไม่มีสินค้าในร้าน</p>";
        }
        ?>
        </div>
    </div>

    <div class="recommended">
        <h3>เมนูแนะนำ</h3>
        <div class="shops">
            <?php
        if (!empty($products)) {
            foreach ($products as $row) {
                $image_url = htmlspecialchars($row['image_url']);
                $product_name = htmlspecialchars($row['product_name']);
                $price = number_format($row['price'], 2);

                echo '<a href="user_menu.php?product_id=' . $row['product_id'] . '" style="text-decoration: none; color: inherit;">
                <div class="shop">
                    <img src="' . $image_url . '" alt="' . $product_name . '">
                    <div class="menu-item">
                        <span>' . $product_name . '</span>
                        <p>' . $price . '฿</p>
                    </div>
                </div>
            </a>';
            }
        } else {
            echo "<p>ไม่มีสินค้าในร้าน</p>";
        }
        ?>
        </div>
    </div>

    <footer class="footer">
        <div class="footer-item active">
            <i class="fa-solid fa-house-chimney"></i>&nbsp;
            <p>HOME</p>
        </div>
        <div class="footer-item">
            <i class="fa-solid fa-file-alt"></i>
        </div>
        <div class="footer-item">
            <i class="fa-solid fa-cart-shopping"></i>
        </div>
        <div class="footer-item notification">
            <i class="fa-solid fa-bell"></i>
            <span class="notification-badge"></span>
        </div>
    </footer>
</body>

</html>