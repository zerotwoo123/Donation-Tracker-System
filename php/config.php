<?php
// Check if session is not already active before starting it
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$servername = "localhost"; // Replace with your server name
$username = "root"; // Replace with your username
$password = ""; // Replace with your password
$dbname = "donation"; // Replace with your database name

// Create connection
$con = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}
try {
    // Create a new PDO instance to establish a database connection
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // Set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    // If an error occurs during connection, you can handle it here
    echo "Connection failed: " . $e->getMessage();
}
?>
