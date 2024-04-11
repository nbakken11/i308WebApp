<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Dates</title>
    <link rel="stylesheet" href="style.css"> <!-- Make sure the file path is correct -->
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
        <h2>Search Orders by Date</h2>
        <?php
        // Initialize database connection variables
        $servername = "db.luddy.indiana.edu";
        $username = "i308s24_nbakken"; // replace with your database username
        $password = "my+sql=i308s24_nbakken"; // replace with your database password
        $dbname = "i308s24_nbakken"; // replace with your database name

        // Create connection
        $con = new mysqli($servername, $username, $password, $dbname);

        // Check connection
        if ($con->connect_error) {
            die("Connection failed: " . $con->connect_error);
        }

        // Get the first and last dates from orders
        $sql = "SELECT MIN(date) AS first_date, MAX(date) AS last_date FROM orders";
        $result = $con->query($sql);

        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $first_date = date("m-d-Y", strtotime($row['first_date']));
            $last_date = date("m-d-Y", strtotime($row['last_date']));
            echo "<p>Filter orders from <strong>$first_date</strong> to <strong>$last_date</strong>.</p>";
        } else {
            echo "<p>Unable to retrieve order dates.</p>";
        }

        ?>

        <form action="order_dates_result.php" method="post">
            Start Date: <input type="date" name="start_date" required>
            End Date: <input type="date" name="end_date" required>
            <input type="submit" value="Search">
        </form>
    </div>
    <?php $con->close(); ?>
</body>
</html>
