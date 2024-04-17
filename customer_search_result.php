<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Search by Last Name</title>
    <style>
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
    <h2>Search Inventory by Customer's Last Name</h2>
    <form action="" method="post">
        Last Name: <input type="text" name="lname" required>
        <input type="submit" name="submit" value="Search">
    </form>

    <div class="container">
        <div>
            <h3>Customer Names:</h3>
            <table>
                <?php
                $servername = "db.luddy.indiana.edu";
                $username = "i308s24_nbakken";
                $password = "my+sql=i308s24_nbakken";
                $dbname = "i308s24_nbakken";
                $con = mysqli_connect($servername, $username, $password, $dbname);
                if (!$con) {
                    die("Connection failed: " . mysqli_connect_error());
                }
                $query = "SELECT CONCAT(fname, ' ', lname) AS customer_name FROM customers";
                $result = mysqli_query($con, $query);
                if (mysqli_num_rows($result) > 0) {
                    echo "<tr><th>Customer Names</th></tr>";
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr><td>" . $row['customer_name'] . "</td></tr>";
                    }
                } else {
                    echo "<tr><td>No customers found.</td></tr>";
                }
                mysqli_close($con);
                ?>
            </table>
        </div>

        <div>
            <?php
            if (isset($_POST['submit'])) {
                $servername = "db.luddy.indiana.edu";
                $username = "i308s24_nbakken";
                $password = "my+sql=i308s24_nbakken";
                $dbname = "i308s24_nbakken";
                $con = mysqli_connect($servername, $username, $password, $dbname);
                if (!$con) {
                    die("Connection failed: " . mysqli_connect_error());
                }
                $lname = mysqli_real_escape_string($con, $_POST['lname']);
                $stmt = $con->prepare("SELECT CONCAT(c.fname, ' ', c.lname) AS customer_name, 
                    SUM(i.quantity) AS total_quantity,
                    SUM(i.sale_price) AS total_sale_price,
                    SUM(i.purchase_cost) AS total_purchase_cost
                    FROM customers AS c
                    JOIN inventory AS i ON c.id = i.cust_id
                    WHERE c.lname = ?
                    GROUP BY c.id");
                $stmt->bind_param("s", $lname);
                $stmt->execute();
                $result = $stmt->get_result();
                if ($result->num_rows > 0) {
                    echo "<h3>Search Results for $lname:</h3>";
                    echo "<table>";
                    echo "<tr><th>Customer Name</th><th>Total Quantity</th><th>Total Sale Price</th><th>Total Purchase Cost</th></tr>";
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row['customer_name'] . "</td>";
                        echo "<td>" . $row['total_quantity'] . "</td>";
                        echo "<td>" ."$" . $row['total_sale_price'] . "</td>";
                        echo "<td>" . $row['total_purchase_cost'] . "</td>";
                        echo "</tr>";
                    }
                    echo "</table>";
                } else {
                    echo "No results found for $lname.";
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
