<?php
session_start();
require '../php/config.php';
$conn = new mysqli('localhost', 'root', '', 'donation');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['logID'])) {
    $logID = $_POST['logID'];
    
    // Delete the log
    $query = "DELETE FROM activity_log WHERE logID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $logID);

    if ($stmt->execute()) {
        // Log the activity
        $adminID = $_SESSION['adminID'];
        $time = date('Y-m-d H:i:s');
        $action = 'delete';
        $affected = $logID;
        $details = "Deleted log with ID: $logID";
        
        $logQuery = "INSERT INTO activity_log (adminID, time, action, affected, details) VALUES (?, ?, ?, ?, ?)";
        $logStmt = $conn->prepare($logQuery);
        $logStmt->bind_param("issss", $adminID, $time, $action, $affected, $details);
        $logStmt->execute();
        
        echo 'success';
    } else {
        echo 'error';
    }

    $stmt->close();
}

$conn->close();
?>
