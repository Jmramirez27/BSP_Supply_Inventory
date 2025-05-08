<?php
// Connect to MySQL
$conn = new mysqli("localhost", "root", "", "bspsupply_mngt");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set headers to trigger file download
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="inventory_export.csv"');

// Output to browser
$output = fopen("php://output", "w");

// Write CSV column headers
fputcsv($output, ['Item No', 'Particular', 'Quantity', 'Unit', 'Date', 'Division Requested','Total_Item Requested']);

// Fetch and write rows
$result = $conn->query("SELECT * FROM inventory");
while ($row = $result->fetch_assoc()) {
    fputcsv($output, $row);
}

fclose($output);
exit;
?>