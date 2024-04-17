<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

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
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];

    // SQL to check the first name and last name
    $sql = "SELECT * FROM employee WHERE fname = ? AND lname = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("ss", $fname, $lname);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $_SESSION['logged_in'] = true;
        header('Location: edit-completion-date.php');
        exit();
    } else {
        echo "<p>Invalid credentials! Please try again.</p>";
    }
    $stmt->close();
}

// Fetch employee names
$employeeNames = array();
$sql = "SELECT fname, lname FROM employee";
$result = $con->query($sql);
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $employeeNames[] = $row['fname'] . ' ' . $row['lname'];
    }
}

$con->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Employee Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php include 'nav.html'; ?>
    <div class="form-container1">
        <form method="post" action="login.php">
            <h2>Employee Login</h2>
            <p>Username: Employee First Name</p>
            <p>Password: Employee Last Name</p>
            <label for="fname">Username:</label>
            <input type="text" id="fname" name="fname" required>
            <br>
            
            <label for="lname">Password:</label>
            <input type="text" id="lname" name="lname" required>
            
            <input type="submit" value="Login">
        </form>
    </div>

    <div class="employee-list">
        <h2>Employee Names</h2>
        <ul>
            <?php foreach ($employeeNames as $name): ?>
                <li><?php echo $name; ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
</body>
</html>
