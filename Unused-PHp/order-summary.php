<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Database connection details
$servername = "db.luddy.indiana.edu";
$username = "i308s24_nbakken"; // Replace with your database username
$password = "my+sql=i308s24_nbakken"; // Replace with your database password
$dbname = "i308s24_nbakken"; // Replace with your database name

// Create connection
$con = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($con->connect_error) {
    die("Connection failed: " . mysqli_connect_error());
}

$sql = "SELECT ps.id AS order_id, ps.estimate, ps.expected_completion, ps.completion_date, c.fname AS customer_fname,
        c.lname AS customer_lname, i.name AS item_name, i.description AS item_description
        FROM prob_sheet AS ps
        JOIN customers AS c ON ps.cust_id = c.id
        JOIN item AS i ON ps.item_id = i.id
        ORDER BY ps.expected_completion";

$result = $con->query($sql);

// Function that will generate a completion form. To be replaced with actual update logic.
function generateCompletionButton($orderId) {
    // This would point to a PHP script that handles the update. For example: update-order-status.php
    $form = "<form method='POST' action='update-order-status.php'>";
    $form .= "<input type='hidden' name='order_id' value='" . $orderId . "'>";
    $form .= "<input type='submit' name='mark_complete' value='Mark as Complete'>";
    $form .= "</form>";
    return $form;
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Order Details</title>
    <link rel="stylesheet" href="style.css"> <!-- Main stylesheet link -->
    <link rel="stylesheet" href="table-styles.css"> <!-- Table-specific stylesheet link -->
</head>

<body>
<?php include 'nav.html'; ?>


<div class="search-container">
    <h2>Customer Problem Sheet Details</h2>
    <?php
    if ($result && $result->num_rows > 0) {
        echo "<table>";
        echo "<tr><th>Order ID</th><th>Customer Name</th><th>Item Name</th><th>Item Description</th><th>Estimated Cost</th><th>Expected Completion</th><th>Completion Date</th></tr>";
        while($row = $result->fetch_assoc()) {
            $completion_date = $row['completion_date'] ? $row['completion_date'] : "Pending";
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['order_id']) . "</td>";
            echo "<td>" . htmlspecialchars($row['customer_fname']) . " " . htmlspecialchars($row['customer_lname']) . "</td>";
            echo "<td>" . htmlspecialchars($row['item_name']) . "</td>";
            echo "<td>" . htmlspecialchars($row['item_description']) . "</td>";
            echo "<td>$" . htmlspecialchars($row['estimate']) . "</td>";
            echo "<td>" . htmlspecialchars($row['expected_completion']) . "</td>";
            echo "<td>" . $completion_date . "</td>";
            // Additional columns for actions can go here
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p>No problem sheet records found.</p>";
    }
    $con->close();
    ?>
</div>
</body>
</html>