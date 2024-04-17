<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Order Details</title>
    <link rel="stylesheet" href="style.css"> 
    <link rel="stylesheet" href="table-styles.css"> 
</head>
<body>
<?php include 'nav.html'; ?>


    <div class="search-container">
        <h2>Customer Problem Sheet Details</h2>
        <table>
        </table>
    </div>
</body>
</html><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- <title>Edit Completion Dates</title> -->
    <link rel="stylesheet" href="style.css"> 
    <link rel="stylesheet" href="table-styles.css"> 
</head>
<body>

<div class="search-container">
    <!-- <h2>Edit Completion Dates</h2> -->
    <table>
        <tr>
            <th>Order ID</th>
            <th>Customer Name</th>
            <th>Item Name</th>
            <th>Item Description</th>
            <th>Estimate</th>
            <th>Expected Completion</th>
            <th>Actual Completion</th>
            <th>Update Completion Date</th>
        </tr>

        <?php
        // Database connection
        $servername = "db.luddy.indiana.edu";
        $username = "i308s24_nbakken";
        $password = "my+sql=i308s24_nbakken";
        $dbname = "i308s24_nbakken";
        $con = new mysqli($servername, $username, $password, $dbname);
        if ($con->connect_error) {
            die("Connection failed: " . $con->connect_error);
        }

        // SQL query with subquery
        $sql = "SELECT ps.id AS order_id, ps.estimate, ps.expected_completion, ps.completion_date, c.fname AS customer_fname,
                c.lname AS customer_lname, i.name AS item_name, i.description AS item_description
                FROM prob_sheet AS ps
                JOIN customers AS c ON ps.cust_id = c.id
                JOIN item AS i ON ps.item_id = i.id
                WHERE ps.id IN (
                    SELECT ps.id
                    FROM prob_sheet AS ps
                    JOIN customers AS c ON ps.cust_id = c.id
                    JOIN item AS i ON ps.item_id = i.id
                    ORDER BY ps.expected_completion
                )
                ORDER BY ps.expected_completion";

        $result = $con->query($sql);

        if ($result !== false && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row['order_id'] . "</td>";
                echo "<td>" . $row['customer_fname'] . " " . $row['customer_lname'] . "</td>";
                echo "<td>" . $row['item_name'] . "</td>";
                echo "<td>" . $row['item_description'] . "</td>";
                echo "<td>" . "$".$row['estimate'] . "</td>";
                echo "<td>" . $row['expected_completion'] . "</td>";
                echo "<td>" . $row['completion_date'] . "</td>";
                echo "<td>";
                echo "<form action='update-completion-date.php' method='post'>";
                echo "<input type='hidden' name='order_id' value='" . $row['order_id'] . "'>";
                echo "<input type='date' name='new_date' value='" . $row['completion_date'] . "'>";
                echo "<input type='submit' value='Update' style='width: 150px;'>"; // Wider button
                echo "</form>";
                echo "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='8'>No records found.</td></tr>";
        }
        $con->close();
        ?>
    </table>
</div>

</body>
</html>
