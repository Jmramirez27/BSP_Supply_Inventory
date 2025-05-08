<?php
// Connection
$conn = new mysqli("localhost", "root", "", "bspsupply_mngt");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$success_msg = "";
$error_msg = "";

// Handle delete request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['item_num'])) {
    $item_num = $_POST['item_num'];

    $stmt = $conn->prepare("DELETE FROM inventory WHERE item_num = ?");
    $stmt->bind_param("s", $item_num);

    if ($stmt->execute()) {
        $success_msg = "Item successfully deleted!";
    } else {
        $error_msg = "Error deleting record: " . $stmt->error;
    }

    $stmt->close();
}

// Get item number via GET to show confirmation form
$item_num = $_GET['item_num'] ?? '';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Delete Item</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h3>Delete Inventory Item</h3>

        <?php if ($success_msg): ?>
            <div class="alert alert-success"><?= $success_msg ?></div>
            <a href="inventory.php" class="btn btn-primary mt-2">Back to Inventory</a>
        <?php elseif ($error_msg): ?>
            <div class="alert alert-danger"><?= $error_msg ?></div>
        <?php elseif ($item_num): ?>
            <div class="alert alert-warning">
                Are you sure you want to delete this item (Item #<?= htmlspecialchars($item_num) ?>)?
            </div>
            <form method="POST">
                <input type="hidden" name="item_num" value="<?= htmlspecialchars($item_num) ?>">
                <button type="submit" class="btn btn-danger">Yes, Delete</button>
                <a href="inventory.php" class="btn btn-secondary">Cancel</a>
            </form>
        <?php else: ?>
            <div class="alert alert-info">No item selected to delete.</div>
            <a href="inventory.php" class="btn btn-secondary">Back</a>
        <?php endif; ?>
    </div>
</body>
</html>
