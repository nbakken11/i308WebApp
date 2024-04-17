<?php
session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

$servername = "db.luddy.indiana.edu";
$username = "i308s24_nbakken";
$password = "my+sql=i308s24_nbakken";
$dbname = "i308s24_nbakken";
$con = new mysqli($servername, $username, $password, $dbname);
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['order_id']) && isset($_POST['new_date'])) {
    $order_id = $_POST['order_id'];
    $new_date = $_POST['new_date'];

    $sql = "UPDATE prob_sheet SET completion_date = ? WHERE id = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("si", $new_date, $order_id);
    $stmt->execute();
    if ($stmt->affected_rows > 0) {
        echo "Completion date updated successfully.";
    } else {
        echo "Error updating record: " . $con->error;
    }
    $stmt->close();
    $con->close();
    header("Location: edit-completion-date.php"); 
    exit;
} else {
    echo "Invalid request";
}
