<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css"> 
    <style>
        /* Add your custom styles here */
        .container {
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        table {
            margin-top: 20px;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #dddddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        /* Increase width of the search button */
        input[type="submit"] {
            width: 150px; /* Adjust the width as needed */
        }
    </style>
</head>
<body>
<?php include 'nav.html'; ?>
<div class="container">
    <h2>Order-Supplier Relationship Details</h2>
    <form method="post" action="">
        <label for="supplier">Select Supplier:</label>
        <select name="supplier" id="supplier">
            <?php
            // Establish a new database connection
            $con = mysqli_connect("db.luddy.indiana.edu", "i308s24_nbakken", "my+sql=i308s24_nbakken", "i308s24_nbakken");
            if (!$con) {
                die("Connection failed: " . mysqli_connect_error());
            }

            // Create the query to retrieve all distinct supplier names
            $query = "SELECT DISTINCT name FROM supplier";
            $result = mysqli_query($con, $query);

            // Check if there are results
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<option value=\"" . $row['name'] . "\">" . $row['name'] . "</option>";
                }
            } else {
                echo "<option value=\"\">No suppliers found</option>";
            }

            // Close connection
            mysqli_close($con);
            ?>
        </select>
        <!-- Increased width for the search button -->
        <input type="submit" name="submit" value="Search" style="width: 150px;">
    </form>
</div>

<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Database connection details
$servername = "db.luddy.indiana.edu";
$username = "i308s24_nbakken"; 
$password = "my+sql=i308s24_nbakken"; 
$dbname = "i308s24_nbakken"; 

// Create connection
$con = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $supplier = $_POST['supplier'];

    $sql = "SELECT o.id AS order_id, s.name AS supplier_name, i.name AS item_name, o.date AS order_date, SUM(od.quantity) AS total_quantity
            FROM orders AS o
            JOIN supplier AS s ON o.supplier_id = s.id
            JOIN order_details AS od ON o.id = od.cart_id
            JOIN item AS i ON od.inventory_id = i.id
            WHERE s.name = ?
            GROUP BY o.id, s.id, i.id";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("s", $supplier);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<div class='container'>";
        echo "<table>";
        echo "<tr><th>Order ID</th><th>Supplier Name</th><th>Item Name</th><th>Order Date</th><th>Total Quantity</th></tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row['order_id'] . "</td>";
            echo "<td>" . $row['supplier_name'] . "</td>";
            echo "<td>" . $row['item_name'] . "</td>";
            echo "<td>" . $row['order_date'] . "</td>";
            echo "<td>" . $row['total_quantity'] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
        echo "</div>";
    } else {
        echo "<p>No results found for $supplier.</p>";
    }

    $stmt->close();
}
$con->close();
?>



</body>
</html>
