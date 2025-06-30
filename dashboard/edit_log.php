<?php
session_start();
require '../php/config.php';
$conn = new mysqli('localhost', 'root', '', 'donation');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['logID'], $_POST['newDetails'])) {
    $logID = $_POST['logID'];
    $newDetails = $_POST['newDetails'];
    
    // Update the log details
    $query = "UPDATE activity_log SET details = ? WHERE logID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("si", $newDetails, $logID);

    if ($stmt->execute()) {
        // Log the activity
        $adminID = $_SESSION['adminID'];
        $time = date('Y-m-d H:i:s');
        $action = 'edit';
        $affected = $logID;
        $details = "Updated log details to: $newDetails";
        
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
