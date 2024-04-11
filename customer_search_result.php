<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css"> <!-- Make sure the file path is correct -->
    <title>Search by Last Name</title>
    <style>
        /* Style for the tables */
        .container {
            display: flex;
        }
        .container > div {
            flex: 1;
            padding: 10px;
        }
        table {
            width: 100%;
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
        <h2>Search Inventory by Customer's Last Name</h2>
        <form action="" method="post">
            Last Name: <input type="text" name="lname" required>
            <input type="submit" name="submit" value="Search">
        </form>

        <!-- Display the search results -->
        <div class="container">
            <!-- Last names table -->
            <div>
                <h3>Last Names of Customers:</h3>
                <table>
                    <?php
                    // Initialize variables for the database connection
                    $servername = "db.luddy.indiana.edu";
                    $username = "i308s24_nbakken"; // Replace with your database username
                    $password = "my+sql=i308s24_nbakken"; // Replace with your database password
                    $dbname = "i308s24_nbakken"; // Replace with your database name

                    // Establish a new database connection
                    $con = mysqli_connect($servername, $username, $password, $dbname);
                    if (!$con) {
                        die("Connection failed: " . mysqli_connect_error());
                    }

                    // Create the query to retrieve last names of all employees
                    $query = "SELECT lname FROM customers";
                    $result = mysqli_query($con, $query);

                    // Check if there are results
                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<tr><td>" . $row['lname'] . "</td></tr>";
                        }
                    } else {
                        echo "<tr><td>No employees found.</td></tr>";
                    }

                    // Close connection
                    mysqli_close($con);
                    ?>
                </table>
            </div>
            
            <!-- Search results table -->
            <div>
                <?php
                if (isset($_POST['submit'])) {
                    // Initialize variables for the database connection
                    $servername = "db.luddy.indiana.edu";
                    $username = "i308s24_nbakken"; // Replace with your database username
                    $password = "my+sql=i308s24_nbakken"; // Replace with your database password
                    $dbname = "i308s24_nbakken"; // Replace with your database name

                    // Establish a new database connection
                    $con = mysqli_connect($servername, $username, $password, $dbname);
                    if (!$con) {
                        die("Connection failed: " . mysqli_connect_error());
                    }

                    // Sanitize the input to protect against SQL injections
                    $lname = mysqli_real_escape_string($con, $_POST['lname']);

                    // Create the query using prepared statements
                    $stmt = $con->prepare("SELECT i.id, i.quantity, i.sale_price, i.purchase_cost, i.description, c.fname, c.lname, cp.phone, ce.email FROM inventory AS i JOIN customers AS c ON i.cust_id = c.id LEFT JOIN customer_phone AS cp ON c.id = cp.cust_id LEFT JOIN customer_email AS ce ON c.id = ce.cust_email WHERE c.lname = ?");
                    $stmt->bind_param("s", $lname);

                    // Execute the query
                    $stmt->execute();
                    $result = $stmt->get_result();

                    // Check if there are results
                    if ($result->num_rows > 0) {
                        // Start the table to display the results
                        echo "<h3>Search Results for $lname:</h3>";
                        echo "<table>";
                        echo "<tr><th>Customer Name</th><th>Phone</th><th>Email</th>";
                        // <th>Purchase Cost</th></tr>

                        // Output data of each row
                        while($row = $result->fetch_assoc()) {
                            echo "<tr>";
                           
                            echo "<td>" . $row['fname'] . " " . $row['lname'] . " (" . $row['id'] . ")" . "</td>";
                            // echo "<td>" . $row['description'] . "</td>";
                            // echo "<td>" . $row['quantity'] . "</td>";
                            // echo "<td>" . $row['sale_price'] . "</td>";
                            // echo "<td>" . $row['purchase_cost'] . "</td>";
                            echo "<td>" . "(812". $row['phone'] . "</td>";
                            echo "<td>" . $row['email'] . "</td>";
                            echo "</tr>";
                        }
                        echo "</table>";
                    } else {
                        echo "No results found for $lname.";
                    }

                    // Close statement and connection
                    $stmt->close();
                    $con->close();
                }
                ?>
            </div>
        </div>
    </div>
</body>
</html>
