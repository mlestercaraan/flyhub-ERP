<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}
$conn = new mysqli("localhost", "root", "", "crud_demo");
if ($conn->connect_error) { die("Connection failed: " . $conn->connect_error); }

$name = $_POST['company_name'] ?? '';
$city = $_POST['city'] ?? '';
$country = $_POST['country'] ?? '';
$website = $_POST['website_url'] ?? '';

$stmt = $conn->prepare("INSERT INTO companies (company_name, city, country, website_url) VALUES (?, ?, ?, ?)");
$stmt->bind_param('ssss', $name, $city, $country, $website);
$stmt->execute();
$stmt->close();
$conn->close();

header("Location: companies.php");
exit();
?>
