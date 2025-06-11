<?php
$conn = new mysqli("localhost", "root", "", "crud_demo");
if ($conn->connect_error) { die("Connection failed: " . $conn->connect_error); }

$id = $_GET['id'];
$sql = "DELETE FROM contacts WHERE id=$id";
$conn->query($sql);

header("Location: index.php");
exit();
?>
