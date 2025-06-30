<?php
session_start();

// Check if super admin is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['role'] !== 'super_admin') {
    header("Location: ../login/login.php"); // Redirect to login page if not logged in
    exit;
}

require '../php/config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $adminID = $_POST['admin_id'];
    $role = $_POST['role'];

    // Check the SQL query
    $sql = "UPDATE admin_tbl SET role = ? WHERE adminID = ?";
    
    // Prepare and execute the statement
    $stmt = $con->prepare($sql);
    $stmt->bind_param("si", $role, $adminID);
    
    if ($stmt->execute()) {
        echo "Role assigned successfully!";
    } else {
        echo "Error: " . $stmt->error; // Check for any errors during execution
    }

    $stmt->close();
    $con->close();
}
?>
