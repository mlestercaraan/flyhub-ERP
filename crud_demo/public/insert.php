<?php
// Connect to database
$conn = new mysqli("localhost", "root", "", "crud_demo");
if ($conn->connect_error) { die("Connection failed: " . $conn->connect_error); }

// Get data from form
$first_name = $_POST['first_name'];
$last_name  = $_POST['last_name'];
$email      = $_POST['email'];
$phone      = $_POST['phone'];

// Insert into database
$sql = "INSERT INTO contacts (first_name, last_name, email, phone)
        VALUES ('$first_name', '$last_name', '$email', '$phone')";
$conn->query($sql);

// Redirect back to index
header("Location: index.php");
exit();
?>
