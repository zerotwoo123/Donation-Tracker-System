<?php
session_start();

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Check if user is logged in
    if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
        echo json_encode(['success' => false, 'message' => 'User not logged in']);
        exit;
    }

    // Check if the 'id' parameter is sent in the POST request
    if (!isset($_POST['id'])) {
        echo json_encode(['success' => false, 'message' => 'Donation ID not provided']);
        exit;
    }

    // Sanitize the 'id' parameter to prevent SQL injection
    $donateID = intval($_POST['id']); // Cast to integer

    // Database connection
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "donation";

    // Create connection
    $con = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($con->connect_error) {
        echo json_encode(['success' => false, 'message' => 'Connection failed: ' . $con->connect_error]);
        exit;
    }

    // Prepare and execute the DELETE query
    $sql = "DELETE FROM donationinfo_tbl WHERE donateID = ?";
    $stmt = $con->prepare($sql);
    
    if (!$stmt) {
        echo json_encode(['success' => false, 'message' => 'Prepare statement failed: ' . $con->error]);
        exit;
    }

    $stmt->bind_param("i", $donateID); // Assuming donateID is an integer

    if (!$stmt->execute()) {
        // Error occurred during deletion
        echo json_encode(['success' => false, 'message' => 'Error deleting donor: ' . $stmt->error]);
        exit;
    }

    // Close the prepared statement and the database connection
    $stmt->close();
    $con->close();

    // Return JSON response indicating success
    echo json_encode(['success' => true]);
} else {
    // If the request method is not POST, return an error response
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>
