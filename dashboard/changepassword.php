<?php
session_start();
include('../php/config.php'); // Include your database connection file

if(isset($_POST['newPassword']) && isset($_POST['confirmPassword']) && isset($_POST['userEmail'])) {
    $newPassword = $_POST['newPassword'];
    $confirmPassword = $_POST['confirmPassword'];
    $userEmail = $_POST['userEmail'];

    // Establish database connection
    $con = mysqli_connect("localhost", "root", "", "donation");

    // Check if the connection was successful
    if (mysqli_connect_errno()) {
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
        exit();
    }

    // Check if passwords match
    if($newPassword !== $confirmPassword) {
        echo "Passwords do not match";
        exit();
    } else {
        // Check if the new password is different from the current one
        $checkQuery = "SELECT password FROM admin_tbl WHERE email='$userEmail'";
        $checkResult = mysqli_query($con, $checkQuery);
        $row = mysqli_fetch_assoc($checkResult);
        $currentPassword = $row['password'];

        if(password_verify($newPassword, $currentPassword)) {
            echo "New password cannot be the same as the current one";
            exit();
        }

        // Hash the new password
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

        // Update the password in the admin_tbl
        $sql = "UPDATE admin_tbl SET password='$hashedPassword' WHERE email='$userEmail'";
        if(mysqli_query($con, $sql)) {
            echo "Password updated successfully";
            exit();
        } else {
            echo "Error updating password: " . mysqli_error($con);
        }
    }
}
?>
