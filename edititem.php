<?php
// Connect to the database
$conn = new mysqli("localhost", "root", "", "bspsupply_mngt");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize variables
$item_num = "";
$success_msg = "";
$error_msg = "";
$row = [
    'Particular' => '',
    'Quantity' => '',
    'Unit' => '',
    'Date' => '',
    'Div_request' => '',
    'Total_Item_Requested' => ''
];

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $item_num = $_POST['item_num'];
    $particular = $_POST['Particular'];
    $quantity = $_POST['Quantity'];
    $unit = $_POST['Unit'];
    $date = $_POST['Date'];
    $div_request = $_POST['Div_request'];
    $total_itemrequest = $_POST['Total_Item_Requested'];

    // Prepare and execute update
    $stmt = $conn->prepare("UPDATE inventory SET Particular=?, Quantity=?, Unit=?, Date=?, Div_request=?, Total_Item_Requested=? WHERE item_num=?");
    $stmt->bind_param("sissssi", $particular, $quantity, $unit, $date, $div_request, $total_itemrequest, $item_num);

    if ($stmt->execute()) {
        $success_msg = "Item updated successfully!";
    } else {
        $error_msg = "Error updating item: " . $stmt->error;
    }

    $stmt->close();
}

// Fetch the item data
if (isset($_GET['item_num']) || isset($_POST['item_num'])) {
    $item_num = $_GET['item_num'] ?? $_POST['item_num'];
    $sql = "SELECT * FROM inventory WHERE item_num = '$item_num'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
    } else {
        $error_msg = "Item not found.";
    }
} else {
    $error_msg = "No item selected.";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Item</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
</head>
<body class="container mt-4">
    <h3>Update Inventory Item</h3>

    <?php if ($success_msg): ?>
        <div class="alert alert-success"><?= $success_msg ?></div>
    <?php elseif ($error_msg): ?>
        <div class="alert alert-danger"><?= $error_msg ?></div>
    <?php endif; ?>

    <?php if (!$error_msg): ?>
    <form method="POST" action="">
        <input type="hidden" name="item_num" value="<?= htmlspecialchars($item_num) ?>">

        <div class="mb-3">
            <label class="form-label">Particular</label>
            <input type="text" class="form-control" name="Particular" value="<?= htmlspecialchars($row['Particular']) ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Quantity</label>
            <input type="number" class="form-control" name="Quantity" value="<?= htmlspecialchars($row['Quantity']) ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Unit</label>
            <input type="text" class="form-control" name="Unit" value="<?= htmlspecialchars($row['Unit']) ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Date</label>
            <input type="date" class="form-control" name="Date" value="<?= htmlspecialchars($row['Date']) ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Division Requested</label>
            <select class="form-select" name="Div_request" required>
                <option value="">-- Select Division --</option>
                <?php
                $divisions = [
                    "Field Operation Division",
                    "Admin Division",
                    "Finance Division",
                    "Office of the Secretary General",
                    "National Scout Shop",
                    "Property Management Development Division",
                    "Internal Audit",
                    "Central Record Office",
                    "Planning and ICT Unit"
                ];
                foreach ($divisions as $div) {
                    $selected = ($row['Div_request'] == $div) ? 'selected' : '';
                    echo "<option value=\"$div\" $selected>$div</option>";
                }
                ?>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Total Number of Item Requested</label>
            <input type="number" class="form-control" name="Total_Item_Requested" value="<?= htmlspecialchars($row['Total_Item_Requested']) ?>" required>
        </div>

        <button type="submit" class="btn btn-success">Update Item</button>
        <a href="inventory.php" class="btn btn-secondary">Back</a>
    </form>
    <?php endif; ?>
</body>
</html>
