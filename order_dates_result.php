<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

$start_date = isset($_POST['start_date']) ? $_POST['start_date'] : '';
$end_date = isset($_POST['end_date']) ? $_POST['end_date'] : '';

$con = mysqli_connect("db.luddy.indiana.edu", "i308s24_nbakken", "my+sql=i308s24_nbakken", "i308s24_nbakken");
if (!$con) {
    die("Failed to connect to MySQL: " . mysqli_connect_error());
}

$query = "SELECT id, date, notes, supplier_id FROM orders WHERE date BETWEEN ? AND ? ORDER BY date ASC";
$stmt = mysqli_prepare($con, $query);
mysqli_stmt_bind_param($stmt, "ss", $start_date, $end_date);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #e0f7fa;
        }
        .navbar {
            overflow: hidden;
            background-color: #00bcd4;
        }
        .navbar a {
            float: left;
            display: block;
            color: white;
            text-align: center;
            padding: 14px 20px;
            text-decoration: none;
        }
        .navbar a:hover {
            background-color: #007c91;
        }
        .search-container {
            padding: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        tr:hover {
            background-color: #e8f4f8;
        }
    </style>
</head>
<body>
<div class="navbar">
        <a href="home.php">Home</a>
        <a href="order_dates.php">Order Dates</a>
        <a href="customer_search_result.php">Customer Search</a>
        <a href="employee_search.php">Employee Search</a>
        <a href="estimates.php">Estimates</a>
        <a href="order-summary.php">Order Summary</a>
        <a href="item-search.php">Item Search</a>
    </div>

    <div class="search-container">
        <h1>Search Results</h1>
        <h2>Orders from <?php echo htmlspecialchars($start_date); ?> to <?php echo htmlspecialchars($end_date); ?></h2>
        <?php
        if ($result && mysqli_num_rows($result) > 0) {
            echo "<table><tr><th>Order ID</th><th>Date</th><th>Notes</th><th>Supplier ID</th></tr>";
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr><td>" . htmlspecialchars($row['id']) . "</td><td>" . htmlspecialchars($row['date']) . "</td><td>" . htmlspecialchars($row['notes']) . "</td><td>" . htmlspecialchars($row['supplier_id']) . "</td></tr>";
            }
            echo "</table>";
        } else {
            echo "<p>No results found for the specified date range.</p>";
        }
        mysqli_close($con);
        ?>
    </div>
</body>
</html>
