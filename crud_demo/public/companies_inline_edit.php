<?php
session_start();
if (!isset($_SESSION['user'])) { exit("Unauthorized."); }
$conn = new mysqli("localhost", "root", "", "crud_demo");
if ($conn->connect_error) { exit("Connection failed"); }

$id = intval($_POST['id'] ?? 0);
$field = $_POST['field'] ?? '';
$value = trim($_POST['value'] ?? '');

$allowed_fields = ['company_name', 'city', 'country', 'website_url'];
if (!in_array($field, $allowed_fields)) { echo "Invalid field"; exit; }

$stmt = $conn->prepare("UPDATE companies SET $field=? WHERE id=?");
$stmt->bind_param('si', $value, $id);
if($stmt->execute()){ echo "OK"; } else { echo "Error updating"; }
$stmt->close();
$conn->close();
?>
