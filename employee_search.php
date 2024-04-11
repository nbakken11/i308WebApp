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
            max-width: 20em; 
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
        <h2>Search Employees by Last Name</h2>
        <form action="" method="post">
            Last Name: <input type="text" name="lname" required>
            <input type="submit" name="submit" value="Search">
        </form>

        <!-- Display the search results -->
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
                    $stmt = $con->prepare("SELECT e.fname, e.lname, e.dob, e.hourly_rate, ep.phone, ee.email FROM employee AS e LEFT JOIN emp_phone AS ep ON e.id = ep.emp_id LEFT JOIN emp_email AS ee ON e.id = ee.emp_id WHERE e.lname = ?");
                    $stmt->bind_param("s", $lname);
                
                    // Execute the query
                    $stmt->execute();
                    $result = $stmt->get_result();
                
                    // Check if there are results
                    if ($result->num_rows > 0) {
                        // Start the table to display the results
                        echo "<h3>Search Results for $lname:</h3>";
                        echo "<table>";
                        echo "<tr><th>First Name</th><th>Last Name</th><th>Date of Birth</th><th>Hourly Rate</th><th>Phone</th><th>Email</th>";
                        // <th>Additional Columns</th></tr>
                
                        // Output data of each row
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $row['fname'] . "</td>";
                            echo "<td>" . $row['lname'] . "</td>";
                            echo "<td>" . $row['dob'] . "</td>";
                            echo "<td>" . "$".$row['hourly_rate'] . ".00".  "</td>";
                            echo "<td>" ."(812) " .  $row['phone'] . "</td>"; // Add phone column
                            echo "<td>" . $row['email'] . "</td>"; // Add email column
                            // Additional columns can be added here if needed
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
        <div class="container">
            <!-- Last names table -->
            <div>
                <h3>Last Names of Employees:</h3>
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
                    $query = "SELECT lname FROM employee";
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
        </div>

            
            
    </div>
</body>
</html>
