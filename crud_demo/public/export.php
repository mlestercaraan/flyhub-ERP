<?php
session_start();
if (!isset($_SESSION['user'])) {
    exit("Unauthorized.");
}
$conn = new mysqli("localhost", "root", "", "crud_demo");
if ($conn->connect_error) { exit("Connection failed"); }

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=contacts.csv');
$output = fopen('php://output', 'w');
fputcsv($output, ['ID','First Name','Last Name','Email','Phone']);

$result = $conn->query("SELECT * FROM contacts ORDER BY id ASC");
while ($row = $result->fetch_assoc()) {
    fputcsv($output, [
        $row['id'],
        $row['first_name'],
        $row['last_name'],
        $row['email'],
        $row['phone']
    ]);
}
fclose($output);
$conn->close();
exit();
?>
