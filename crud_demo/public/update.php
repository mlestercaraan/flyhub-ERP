<?php
$conn = new mysqli("localhost", "root", "", "crud_demo");
if ($conn->connect_error) { die("Connection failed: " . $conn->connect_error); }

$id         = $_POST['id'];
$first_name = $_POST['first_name'];
$last_name  = $_POST['last_name'];
$email      = $_POST['email'];
$phone      = $_POST['phone'];

$sql = "UPDATE contacts SET 
        first_name='$first_name', 
        last_name='$last_name',
        email='$email',
        phone='$phone'
        WHERE id=$id";
$conn->query($sql);

header("Location: index.php");
exit();
?>
