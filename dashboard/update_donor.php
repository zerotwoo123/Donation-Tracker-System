<?php
session_start();

// Check if user is logged in, redirect to login page if not
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: ../login/login.php"); // Redirect to login page if not logged in
    exit;
}

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "donation";

// Create connection
$con = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

// Check if the request method is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if the request is for updating donor information
    if (isset($_POST['donateID']) && isset($_POST['name']) && isset($_POST['age']) && isset($_POST['gender']) && isset($_POST['phoneNumber']) && isset($_POST['email']) && isset($_POST['address'])) {
        // Sanitize the input data to prevent SQL injection
        $donateID = mysqli_real_escape_string($con, $_POST['donateID']);
        $name = mysqli_real_escape_string($con, $_POST['name']);
        $age = mysqli_real_escape_string($con, $_POST['age']);
        $gender = mysqli_real_escape_string($con, $_POST['gender']);
        $phoneNumber = mysqli_real_escape_string($con, $_POST['phoneNumber']);
        $email = mysqli_real_escape_string($con, $_POST['email']);
        $address = mysqli_real_escape_string($con, $_POST['address']);

        // Update the donor information in the database
        $sql = "UPDATE donationinfo_tbl SET name='$name', age='$age', gender='$gender', phone='$phoneNumber', email='$email', address='$address' WHERE donateID='$donateID'";
        if (mysqli_query($con, $sql)) {
            // Return success response
            echo json_encode(array("success" => true));
        } else {
            // Return error response
            echo json_encode(array("success" => false, "error" => "Failed to update donor information: " . mysqli_error($con)));
        }
    } 
    // Check if the request is for updating activity information
    elseif (isset($_POST['adminID']) && isset($_POST['name']) && isset($_POST['date']) && isset($_POST['action'])) {
        // Sanitize the input data to prevent SQL injection
        $adminID = mysqli_real_escape_string($con, $_POST['adminID']);
        $adminName = mysqli_real_escape_string($con, $_POST['name']);
        $adminDate = mysqli_real_escape_string($con, $_POST['date']);
        $adminAction = mysqli_real_escape_string($con, $_POST['action']);
    
        // Update the activity information in the database
        $sql = "UPDATE activity_tbl SET adminID='$adminID', name='$adminName', date='$adminDate', action='$adminAction'";
        if (mysqli_query($con, $sql)) {
            // Return success response
            echo json_encode(array("success" => true));
        } else {
            // Return error response
            echo json_encode(array("success" => false, "error" => "Failed to update activity information: " . mysqli_error($con)));
        }
    } else {
        // Return error response if any required field is missing
        echo json_encode(array("success" => false, "error" => "Missing required fields"));
    }
} else {
    // Return error response if request method is not POST
    echo json_encode(array("success" => false, "error" => "Invalid request method"));
}

?>
