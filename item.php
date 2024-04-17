<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css"> 
    <title>Total Orders for Specific Item</title>
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
<?php include 'nav.html'; ?>

    <div class="search-container">
        <h2>Total Orders for Specific Item</h2>
        <form action="" method="post">
            Select Item:
            <select name="item"> 
                <?php
                $con = mysqli_connect("db.luddy.indiana.edu", "i308s24_nbakken", "my+sql=i308s24_nbakken", "i308s24_nbakken");
                if (!$con) {
                    die("Connection failed: " . mysqli_connect_error());
                }

                $query = "SELECT DISTINCT name FROM item";
                $result = mysqli_query($con, $query);

                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<option value=\"" . $row['name'] . "\">" . $row['name'] . "</option>";
                    }
                } else {
                    echo "<option value=\"\">No items found</option>";
                }

                mysqli_close($con);
                ?>
            </select>

            <input type="submit" name="submit" value="Search">
        </form>

        <!-- Display the search results -->
        <div class="container">
            <div>
                <?php
                if (isset($_POST['submit'])) {
                    $servername = "db.luddy.indiana.edu";
                    $username = "i308s24_nbakken"; 
                    $password = "my+sql=i308s24_nbakken"; 
                    $dbname = "i308s24_nbakken"; 

                    // Establish a new database connection
                    $con = mysqli_connect($servername, $username, $password, $dbname);
                    if (!$con) {
                        die("Connection failed: " . mysqli_connect_error());
                    }

                    // Sanitize the input to protect against SQL injections
                    $item = mysqli_real_escape_string($con, $_POST['item']);

                    // Create the query using prepared statements
                    $stmt = $con->prepare("SELECT i.name AS item_name, s.name AS supplier_name, SUM(od.quantity) AS total_quantity
                                            FROM item AS i
                                            JOIN order_details AS od ON i.id = od.inventory_id
                                            JOIN orders AS o ON od.cart_id = o.id
                                            JOIN supplier AS s ON o.supplier_id = s.id
                                            WHERE i.name = ?
                                            GROUP BY i.id, s.id");
                    $stmt->bind_param("s", $item);

                    // Execute the query
                    $stmt->execute();
                    $result = $stmt->get_result();

                    if ($result->num_rows > 0) {
                        echo "<h3>Total Orders for $item:</h3>";
                        echo "<table>";
                        echo "<tr><th>Item Name</th><th>Supplier Name</th><th>Total Quantity Ordered</th></tr>";

                        while($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $row['item_name'] . "</td>";
                            echo "<td>" . $row['supplier_name'] . "</td>";
                            echo "<td>" . $row['total_quantity'] . "</td>";
                            echo "</tr>";
                        }
                        echo "</table>";
                    } else {
                        echo "No results found for $item.";
                    }

                    $stmt->close();
                    $con->close();
                }
                ?>
            </div>
        </div>
    </div>
</body>
</html>
