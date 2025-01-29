<?php
// Mock Data
$store_name = "ร้านข้าวมันไก่";
$owner_name = "ปัทมา แก้วปานกัน";
$order_count = 1;
$preparing_count = 0;
$completed_count = 0;
$total_sales = 1490;
$store_id = "00001";

$sales_data = [40, 55, 65, 75, 90];
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/js/all.min.js" crossorigin="anonymous">
    </script>
    <style>
    body {
        background-color: #f8f9fa;
    }

    .container {
        margin-top: 5rem;
        padding: 0 20px;
        text-align: center;
        font-weight: bold;
    }

    .summary-box {
        width: fit-content;
        text-align: center;
        background: white;
        padding: 20px 25px;
        border-radius: 10px;
        box-shadow: 0px 2px 10px rgba(0, 0, 0, 0.1);
        margin-bottom: 5px;
    }

    .bottom-menu {
        position: fixed;
        bottom: 0;
        width: 100%;
        background: #FFD700;
        display: flex;
        justify-content: space-around;
        padding: 10px 0;
    }

    .bottom-menu a {
        text-decoration: none;
        color: black;
        font-size: 18px;
    }

    .top-tab {
        width: 100%;
        padding: 30px;
        background-color: #FDDF59;
        position: fixed;
        top: 0;
        left: 0;
        z-index: 1000;
    }

    button {
        background-color: #0448A9;
        border: 0px;
        padding: 0.4rem;
        border-radius: 5px;
        color: white;
        font-size: 14px;
    }

    .fa-angle-down {
        margin-left: 5px;
        margin-top: 5px;
    }

    .fa-chevron-right {
        margin-top: 5px;
    }

    .total-sell p {
        font-size: 12px;
        margin: 0;
    }

    .total-sell {
        padding: 10px;
        border-radius: 10px;
        box-shadow: 0px 2px 10px rgba(0, 0, 0, 0.1);
        margin-bottom: 5px;
        width: fit-content;
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

    .footer div {
        text-align: center;
    }

    .footer img {
        width: 30px;
    }

    .footer p {
        margin-top: 5px;
        font-size: 12px;
    }

    .footer button {
        background: none;
        border: none;
        font-size: 1.5em;
        cursor: pointer;
    }

    .store-id {
        font-size: 1rem;
        margin: 0;
        font-weight: 400;
        color: black;
    }
    </style>
</head>

<body>
    <div class="top-tab"></div>


    <div class="container">
        <div class="row align-items-center">
            <div class="col text-start">
                <h4><?php echo $store_name; ?></h4>
            </div>
            <div class="col text-end">
                <h5><?php echo $owner_name; ?></h5>
            </div>
        </div>

        <div class="row align-items-center">
        <div class="col text-start">
                <p class="store-id">Store_ID <?php echo $store_id; ?></p>
            </div>
            <div class="col text-end">
                <button>แก้ไขข้อมูล</button>
            </div>
        </div>

        <div class="row align-items-center mt-1">
            <div class="col text-start">
                <h6>สถานะคำสั่งซื้อ</h6>
            </div>
            <div class="col text-end">
                <h6>ดูประวัติการขาย <i class="fa-solid fa-chevron-right"></i></h6>
            </div>
        </div>



        <div class="row mt-3">
            <div class="col-4 d-flex flex-column align-items-center">
                <div class="summary-box">
                    <span><?php echo $order_count; ?></span>
                </div>
                <h6>ออเดอร์</h6>
            </div>
            <div class="col-4 d-flex flex-column align-items-center">
                <div class="summary-box">
                    <span><?php echo $preparing_count; ?></span>
                </div>
                <h6>ที่ต้องจัดเตรียม</h6>
            </div>
            <div class="col-4 d-flex flex-column align-items-center">
                <div class="summary-box">
                    <span><?php echo $completed_count; ?></span>
                </div>
                <h6>เสร็จสิ้นแล้ว</h6>
            </div>
        </div>
        <hr>

        <div class="row align-items-center">
            <div class="col text-start">
                <h4>สรุปยอดขาย</h4>
            </div>
            <div class="col text-end">
                <h5>ระยะเวลา<i class="fa-solid fa-angle-down"></i></h5>
            </div>
        </div>
        <br>
        <div class="total-sell">
            <p>ยอดขาย (฿) </p>
            <p><?php echo number_format($total_sales); ?></p>
        </div>
        <div class="row mt-3">
            <canvas id="salesChart"></canvas>
        </div>
    </div>



    <footer class="footer">
            <div class="footer-item active">
                <i class="fa-solid fa-house-chimney"></i>&nbsp;
                <p>HOME</p>
            </div>
            <div class="footer-item ">
                <i class="fa-solid fa-file-alt"></i>
            </div>
            <div class="footer-item ">
                 <i class="fa-solid fa-bell"></i>
            </div>
            <div class="footer-item ">
            <i class="fa-regular fa-folder-open"></i>
            </div>
        </footer>

    <!-- Script แสดงกราฟ Chart.js -->
    <script>
    var ctx = document.getElementById('salesChart').getContext('2d');
    var salesChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['', '', '', '', ''],
            datasets: [{
                label: 'ยอดขาย (บาท)',
                data: <?php echo json_encode($sales_data); ?>,
                backgroundColor: 'rgba(54, 162, 235, 0.5)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
    </script>

    <!-- FontAwesome สำหรับไอคอน -->
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>

</body>

</html>