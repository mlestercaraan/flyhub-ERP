<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}
$searchTerm = isset($_GET['search']) ? trim($_GET['search']) : '';
?>