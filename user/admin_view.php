<?php
require 'db_connection.php';

$result = $conn->query("SELECT * FROM payment_proofs");

echo "<h1>รายการการชำระเงิน</h1>";
echo "<table border='1'>";
echo "<tr><th>ID</th><th>User ID</th><th>File Name</th><th>Upload Time</th><th>Status</th><th>Amount</th></tr>";
while ($row = $result->fetch_assoc()) {
    echo "<tr>";
    echo "<td>{$row['id']}</td>";
    echo "<td>{$row['user_id']}</td>";
    echo "<td><a href='uploads/{$row['file_name']}' target='_blank'>ดูไฟล์</a></td>";
    echo "<td>{$row['upload_time']}</td>";
    echo "<td>{$row['payment_status']}</td>";
    echo "<td>{$row['amount']}</td>";
    echo "</tr>";
}
echo "</table>";
?>
