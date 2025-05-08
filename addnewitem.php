<?php
// Connection details
$servername = "localhost";
$username = "root";
$password = "";
$database = "bspsupply_mngt";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get values from POST
$Item_num = $_POST['item_num'];
$particular = $_POST['Particular'];
$quantity = $_POST['Quantity'];
$unit = $_POST['Unit'];
$date = $_POST['Date'];
$div_request = $_POST['Div_request'];
$total_itemrequest = $_POST['Total_Item_Requested'];

// SQL Insert Query
$sql = "INSERT INTO inventory (item_num, Particular, Quantity, Unit, Date, Div_request, `Total_Item_Requested`) 
        VALUES ('$Item_num', '$particular', '$quantity', '$unit', '$date', '$div_request','$total_itemrequest')";

// Execute and redirect
if ($conn->query($sql) === TRUE) {
    header("Location: addnewitem.html?status=success");
    exit();
} else {
    $error = urlencode("Error: " . $conn->error);
    header("Location: addnewitem.html?status=error&message=$error");
    exit();
}


$conn->close();
?>
