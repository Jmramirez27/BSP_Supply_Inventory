<!DOCTYPE html>
<html>
<head>
    <title>Inventory</title>
    <link rel="stylesheet" href="inventorystyle.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.2.2/css/dataTables.bootstrap5.css">
</head>
<body>
<header>
<nav class="navbar navbar-expand-lg navbar-dark bg-success py-3 shadow-sm rounded-bottom">
    <div class="container-fluid d-flex align-items-center">
        <a class="navbar-brand d-flex align-items-center" href="index.html">
            <img src="pic/OFFICIAL_BSP_Logo.png" alt="BSP Logo" class="logo me-2" style="height: 50px;">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-end" id="navbarSupportedContent">
            <ul class="navbar-nav">
                <li class="nav-item"><a class="nav-link text-white" href="index.html">Home</a></li>
                <li class="nav-item"><a class="nav-link text-white" href="inventory.php">Inventory</a></li>
                <li class="nav-item"><a class="nav-link text-white" href="report.php">Report</a></li>
            </ul>
        </div>
    </div>
</nav>

</header>

<div class="container mt-4">
    <div class="mb-3">
        <a href="exportexcel.php" class="btn btn-primary">Export to Excel</a>
        <a href="rmsi.php" class="btn btn-primary">Export to RMSI</a>
    </div>

    <div class="mb-3">
        <a href="addnewitem.html" class="btn btn-success">Add New Item</a>
    </div>

    <table id="InventorySupply" class="table table-striped" style="width:100%">
        <thead>
            <tr>
                <th>Item Number</th>
                <th>Particular</th>
                <th>Quantity</th>
                <th>Unit</th>
                <th>Date</th>
                <th>Division/Section Requested</th>
                <th>Total Number of Item Requested</th>
                <th>Edit</th> 
                <th>Delete</th> 
            </tr>
        </thead>
        <tbody>
            <?php
            $servername = "localhost";
            $username = "root";
            $password = "";
            $database = "bspsupply_mngt";

            $connection = new mysqli($servername, $username, $password, $database);

            if ($connection->connect_error) {
                die("Connection Failed: " . $connection->connect_error);
            }

            $sql = "SELECT * FROM inventory";
            $result = $connection->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                        <td>{$row['item_num']}</td>
                        <td>{$row['Particular']}</td>
                        <td>{$row['Quantity']}</td>
                        <td>{$row['Unit']}</td>
                        <td>{$row['Date']}</td>
                        <td>{$row['Div_request']}</td>
                        <td>{$row['Total_Item_Requested']}</td>
                        <td><a href='edititem.php?item_num={$row['item_num']}' class='btn btn-sm btn-warning'>Edit</a></td>
                        <td><a href='deleteitem.php?item_num={$row['item_num']}' class='btn btn-sm btn-danger'>Delete</a></td>
                    </tr>";
                }
            } else {
                echo "<tr><td colspan='9' class='text-center'>No items found.</td></tr>";
            }

            $connection->close();
            ?>
        </tbody>
    </table>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/2.2.2/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.2.2/js/dataTables.bootstrap5.js"></script>

<script>
    $(document).ready(function () {
        $('#InventorySupply').DataTable();
    });
</script>
</body>
</html>
