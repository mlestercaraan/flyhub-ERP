<?php
session_start();
if (!isset($_SESSION['user'])) { header("Location: login.php"); exit(); }
$conn = new mysqli("localhost", "root", "", "crud_demo");
if ($conn->connect_error) { die("Connection failed: " . $conn->connect_error); }

if (!empty($_POST['selected_ids'])) {
    $ids = array_map('intval', $_POST['selected_ids']);
    $id_str = implode(',', $ids);
    $conn->query("DELETE FROM companies WHERE id IN ($id_str)");
}
$conn->close();
header("Location: companies.php");
exit();
?>
