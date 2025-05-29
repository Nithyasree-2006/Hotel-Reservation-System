<?php
// db_connection.php

$host = "localhost";
$username = "root";
$password = ""; // XAMPP default password is empty
$database = "hotel_reservation"; // Change to your actual database name

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
