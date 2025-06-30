<?php
// Include the config file to establish the database connection
require '../php/config.php';

// Check if the request method is POST and scheduleID is set
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['scheduleID'])) {
    // Sanitize the scheduleID to prevent SQL injection
    $scheduleID = $_POST['scheduleID']; // No need to escape since using PDO prepared statements

    try {
        // Prepare a delete statement
        $stmt = $conn->prepare("DELETE FROM schdule_tbl WHERE scheduleID = :scheduleID");

        // Bind parameters
        $stmt->bindParam(':scheduleID', $scheduleID);

        // Execute the delete statement
        if ($stmt->execute()) {
            echo "Schedule deleted successfully";
        } else {
            echo "Error deleting schedule";
        }
    } catch(PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    echo "Invalid request";
}
?>
