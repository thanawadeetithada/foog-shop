<?php
$host = "localhost";
$username = "root";
$password = "";
$dbname = "store_management";

$connection = new mysqli($host, $username, $password, $dbname);

if ($connection->connect_error) {
    die("Failed to connect to database: " . $connection->connect_error);
} else {
    // echo "Database connection successful!";
}
?>
