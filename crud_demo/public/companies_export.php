<?php
session_start();
if (!isset($_SESSION['user'])) { exit("Unauthorized."); }
$conn = new mysqli("localhost", "root", "", "crud_demo");
if ($conn->connect_error) { exit("Connection failed"); }

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=companies.csv');
$output = fopen('php://output', 'w');
fputcsv($output, ['ID','Company Name','City','Country','Website URL']);

$result = $conn->query("SELECT * FROM companies ORDER BY id ASC");
while ($row = $result->fetch_assoc()) {
    fputcsv($output, [
        $row['id'],
        $row['company_name'],
        $row['city'],
        $row['country'],
        $row['website_url']
    ]);
}
fclose($output);
$conn->close();
exit();
?>
