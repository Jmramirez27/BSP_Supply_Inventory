<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$database = "bspsupply_mngt";

$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection Failed: " . $conn->connect_error);
}

// Set CSV headers
header('Content-Type: text/csv');
header('Content-Disposition: attachment;filename="RMSI.csv"');

$output = fopen('php://output', 'w');

// Column headers
fputcsv($output, ['RIS No.', 'Responsibility Center Code', 'Stock No.', 'Item', 'Unit', 'Quantity', 'Requested', 'Remaining', 'Unit Cost', 'Amount']);

// Fetch and group data
$sql = "SELECT Particular, Unit, Quantity, Total_Item_Requested, Div_request FROM inventory ORDER BY Particular";
$result = $conn->query($sql);

$items = []; // [Particular => ['qty' => int, 'requests' => []]]
$grand_totals = []; // [Particular => ['qty' => int, 'total_requested' => int]]

while ($row = $result->fetch_assoc()) {
    $item = $row['Particular'];
    
    if (!isset($items[$item])) {
        $items[$item] = [
            'qty' => (int)$row['Quantity'],
            'unit' => $row['Unit'],
            'requests' => []
        ];
        $grand_totals[$item] = ['qty' => (int)$row['Quantity'], 'total_requested' => 0];
    }

    $items[$item]['requests'][] = [
        'Div_request' => $row['Div_request'],
        'requested' => (int)$row['Total_Item_Requested']
    ];

    $grand_totals[$item]['total_requested'] += (int)$row['Total_Item_Requested'];
}

// Output grouped data
foreach ($items as $particular => $itemData) {
    $running_balance = $itemData['qty'];
    foreach ($itemData['requests'] as $request) {
        $requested = $request['requested'];
        $remaining = $running_balance - $requested;

        fputcsv($output, [
            '', // RIS No.
            $request['Div_request'],
            '', // Stock No.
            $particular,
            $itemData['unit'],
            $itemData['qty'],
            $requested,
            $remaining,
            '', // Unit Cost
            ''  // Amount
        ]);

        $running_balance = $remaining;
    }
}

// Grand Total Section
fputcsv($output, []); // blank line
fputcsv($output, ['GRAND TOTAL']);
fputcsv($output, ['Item Particular', 'Quantity', 'Total Requested', 'Remaining (Qty - Requested)']);

foreach ($grand_totals as $item => $data) {
    $remaining = $data['qty'] - $data['total_requested'];
    fputcsv($output, [$item, $data['qty'], $data['total_requested'], $remaining]);
}

fclose($output);
$conn->close();
exit;
?>
