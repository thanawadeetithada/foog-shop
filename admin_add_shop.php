<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id']) || (!in_array($_SESSION['role'], ['admin'])) ) {
    header('Location: index.php'); 
    exit();
}

$sql = "SELECT store_id, store_name, user_name, category, phone, image_url FROM stores";
$result = $conn->query($sql);
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
        padding: 15px;
        border-radius: 10px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        max-width: 250px;
        margin: 10px auto;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: space-between;
    }

    .shop img {
        width: 100%;
        max-width: 200px;
        height: auto;
        border-radius: 10px;
        object-fit: cover;
    }

    .shop .menu-item {
        margin-top: 10px;
        font-size: 1em;
    }

    .shop .menu-item span {
        font-weight: bold;
        font-size: 1.2em;
    }

    .shop .menu-item p {
        font-size: 1.1em;
        color: #333;
        margin-bottom: 10px;
    }

    .shop .toggle-switch {
        margin-top: 10px;
    }

    .shop .menu-item .shop-btn,
    .shop .menu-item .edit-shop-btn {
        background-color: #0448A9;
        border: 0px;
        padding: 0.4rem;
        border-radius: 5px;
        color: white;
        width: fit-content;
        margin-top: 10px;
        cursor: pointer;
        font-size: 1rem;
    }

    .shop .menu-item .edit-shop-btn {
        background-color: red;
    }

    .details-bottom {
        position: fixed;
        bottom: 0;
        left: 0;
        width: 90%;
        padding: 20px;
        z-index: 1000;
    }

    .reorder-button {
        display: block;
        text-align: center;
        background-color: #ffd700;
        color: #333;
        text-decoration: none;
        padding: 10px;
        border-radius: 15px;
        font-size: 1.2rem;
    }

    .reorder-button:hover {
        background-color: #ffc107;
    }

    .shop .menu-item {

        width: 100%;
        margin-top: 10px;
    }

    input:checked+.toggle-slider {
        background-color: #4CAF50;
    }

    input:checked+.toggle-slider:before {
        transform: translateX(20px);
    }

    .menu-item-btn {
        display: flex;
        justify-content: space-evenly;

        a {
            text-decoration: none;
        }
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

    .fa-arrow-left {
        margin-right: 20px;
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
        <i class="fa-solid fa-arrow-left" onclick="window.history.back();" style="cursor: pointer;"></i>
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

    <div class="recommended">
        <h3>ร้านค้าทั้งหมด</h3>
        <div class="shops">
            <?php
       if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $image_url = htmlspecialchars($row['image_url']); 
            $store_name = htmlspecialchars($row['store_name']);
        
            $store_id = htmlspecialchars($row['store_id']);
       
    
            echo '<div class="shop">
                <img src="' . $image_url . '" alt="' . $store_name . '">
             
                <div class="menu-item">
                    <div class="menu-item-btn">
                        <a href="shop_main.php?store_id=<?php echo $store_id; ?>" class="shop-btn">ดูร้านค้า</a>
                        <form method="POST" action="delete_shop_db.php" style="display:inline;">
                            <input type="hidden" name="store_id" value="' . $store_id . '">
                            <button type="submit" class="edit-shop-btn">ลบ</button>
                        </form>
                    </div>
                </div>
            </div>';
    }
    } else {
    echo "<p>ไม่มีร้านค้า</p>";
    }

    ?>
    </div>
    </div>

    <footer class="details-bottom">
        <a href="shop_register.php" class="reorder-button">เพิ่มร้านค้า</a>
    </footer>

</body>

</html>

<?php
$result->free();
$conn->close();

?>