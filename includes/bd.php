<?php
// Database connection file for your app

$DB_HOST = 'localhost';      // XAMPP default is localhost
$DB_USER = 'root';           // XAMPP default is root
$DB_PASS = '';               // XAMPP default is empty password
$DB_NAME = 'crud_demo';      // Your database name

$conn = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);

// Check connection
if ($conn->connect_error) {
    die('Database connection failed: ' . $conn->connect_error);
}

// Optionally set charset (recommended)
$conn->set_charset("utf8mb4");
?>
