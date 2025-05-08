<?php
// report.php
$conn = new mysqli("localhost", "root", "", "bspsupply_mngt");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Filter by month
$filter_month = isset($_GET['filter_month']) ? $_GET['filter_month'] : '';
$filter_condition = "";

if (!empty($filter_month)) {
    $year = date('Y', strtotime($filter_month));
    $month = date('m', strtotime($filter_month));
    $filter_condition = "WHERE MONTH(Date) = '$month' AND YEAR(Date) = '$year'";
}

// Query: Top Requested Particular Items
$sql_items = "
    SELECT Particular, SUM(Quantity) AS total_quantity
    FROM inventory
    $filter_condition
    GROUP BY Particular
    ORDER BY total_quantity DESC
    LIMIT 10";

$result_items = $conn->query($sql_items);
$items = [];
$quantities = [];
while ($row = $result_items->fetch_assoc()) {
    $items[] = $row['Particular'];
    $quantities[] = $row['total_quantity'];
}

// Query: Requests per Division
$sql_divisions = "SELECT Div_request, COUNT(*) as total_requests FROM inventory $filter_condition GROUP BY Div_request ORDER BY total_requests DESC";
$result_divisions = $conn->query($sql_divisions);
$divisions = [];
$requests = [];
while ($row = $result_divisions->fetch_assoc()) {
    $divisions[] = $row['Div_request'];
    $requests[] = $row['total_requests'];
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Inventory Report</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.2.2/css/dataTables.bootstrap5.css">
    <link rel="stylesheet" href="inventorystyle.css">

    <style>
        @media print {
            body {
                margin: 50mm;
                font-size: 12px;
            }

            .no-print, nav, .navbar, .btn, form {
                display: none !important;
            }

            canvas {
                page-break-inside: avoid;
            }

            @page {
                size: Letter landscape;
                margin: 50mm;
            }

            /* Optional: uncomment below to test Legal size instead */
            @page {
                size: Legal landscape;
                margin: 50mm;
            } 
        }
    </style>
</head>
<body>
<header class="no-print">
    <nav class="navbar navbar-expand-lg bg-body-dark navbar-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.html"><img class="logo" src="pic/OFFICIAL_BSP_Logo.png"> </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="index.html">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="inventory.php">Inventory</a></li>
                    <li class="nav-item"><a class="nav-link" href="report.php">Report</a></li>
                </ul>
            </div>
        </div>
    </nav>
</header>

<div class="container mt-4">
    <form method="GET" class="mb-4 d-flex align-items-center gap-2 no-print">
        <label for="filter_month" class="form-label mb-0">Select Month:</label>
        <input type="month" id="filter_month" name="filter_month" class="form-control" style="max-width: 200px;" value="<?= htmlspecialchars($filter_month) ?>" required>
        <button type="submit" class="btn btn-primary">Filter</button>
    </form>

    <div class="text-end mb-4 no-print">
        <button onclick="window.print()" class="btn btn-outline-secondary">🖨️ Print / Save as PDF</button>
    </div>

    <h2>Inventory Data Report <?= $filter_month ? 'for ' . date('F Y', strtotime($filter_month)) : '' ?></h2>

    <div class="mb-5">
        <h4>Top Requested Particular Items</h4>
        <canvas id="topItemsChart" height="100"></canvas>
    </div>

    <div class="mb-5">
        <h4>Top Requesting Divisions</h4>
        <canvas id="topDivisionsChart" height="100"></canvas>
    </div>

    <div class="mb-5">
        <h4>Division Request Distribution</h4>
        <canvas id="divisionPieChart" height="100"></canvas>
    </div>
</div>

<script>
    const topItemsCtx = document.getElementById('topItemsChart').getContext('2d');
    new Chart(topItemsCtx, {
        type: 'bar',
        data: {
            labels: <?= json_encode($items) ?>,
            datasets: [{
                label: 'Total Quantity',
                data: <?= json_encode($quantities) ?>,
                backgroundColor: 'rgba(54, 162, 235, 0.7)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            scales: {
                x: { beginAtZero: true }
            }
        }
    });

    const topDivisionsCtx = document.getElementById('topDivisionsChart').getContext('2d');
    new Chart(topDivisionsCtx, {
        type: 'bar',
        data: {
            labels: <?= json_encode($divisions) ?>,
            datasets: [{
                label: 'Total Requests',
                data: <?= json_encode($requests) ?>,
                backgroundColor: 'rgba(255, 159, 64, 0.7)',
                borderColor: 'rgba(255, 159, 64, 1)',
                borderWidth: 1
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            scales: {
                x: { beginAtZero: true }
            }
        }
    });

    const pieCtx = document.getElementById('divisionPieChart').getContext('2d');
    new Chart(pieCtx, {
        type: 'pie',
        data: {
            labels: <?= json_encode($divisions) ?>,
            datasets: [{
                data: <?= json_encode($requests) ?>,
                backgroundColor: [
                    '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF',
                    '#FF9F40', '#C9CBCF', '#FF6384', '#36A2EB'
                ]
            }]
        },
        options: {
            responsive: true
        }
    });
</script>
</body>
</html>
