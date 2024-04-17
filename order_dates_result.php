<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

$start_date = isset($_POST['start_date']) ? $_POST['start_date'] : '';
$end_date = isset($_POST['end_date']) ? $_POST['end_date'] : '';

$con = mysqli_connect("db.luddy.indiana.edu", "i308s24_nbakken", "my+sql=i308s24_nbakken", "i308s24_nbakken");
if (!$con) {
    die("Failed to connect to MySQL: " . mysqli_connect_error());
}

$query = "SELECT o.id, o.date, o.notes, o.supplier_id, s.name AS supplier_name 
FROM orders AS o 
JOIN supplier AS s ON o.supplier_id = s.id 
WHERE o.date BETWEEN ? AND ? 
ORDER BY o.date ASC";

$stmt = mysqli_prepare($con, $query);
if ($stmt === false) {
    die("Error preparing statement: " . mysqli_error($con));
}

mysqli_stmt_bind_param($stmt, "ss", $start_date, $end_date);
if (!mysqli_stmt_execute($stmt)) {
    die("Error executing statement: " . mysqli_stmt_error($stmt));
}

$result = mysqli_stmt_get_result($stmt);
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Search Results</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #e0f7fa;
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
<?php include 'nav.html'; ?>

<div class="search-container">
    <h1>Search Results</h1>
    <h2>Orders from <?php echo htmlspecialchars($start_date); ?> to <?php echo htmlspecialchars($end_date); ?></h2>
    <?php
    if ($result && mysqli_num_rows($result) > 0) {
        echo "<table><tr><th>Order ID</th><th>Date</th><th>Notes</th><th>Supplier ID</th><th>Supplier Name</th></tr>";
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr><td>" . htmlspecialchars($row['id']) . "</td><td>" . htmlspecialchars($row['date']) . "</td><td>" . htmlspecialchars($row['notes']) . "</td><td>" . htmlspecialchars($row['supplier_id']) . "</td><td>" . htmlspecialchars($row['supplier_name']) . "</td></tr>";
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
