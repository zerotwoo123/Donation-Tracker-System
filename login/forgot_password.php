<?php
include('../php/config.php'); 

if(isset($_POST['signup'])) {
    $email = $_POST['name'];
    $password = $_POST['pass'];
    $re_password = $_POST['re_pass'];

    // Establish database connection
    $con = mysqli_connect("localhost", "root", "", "donation");

    // Check if the connection was successful
    if (mysqli_connect_errno()) {
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
        exit();
    }

    // Check if passwords match
    if($password !== $re_password) {
        echo "<script>
                alert('Passwords do not match');
                window.location.href = 'Forgotpassword.php';
              </script>";
        exit(); // Ensure that no further output is sent
    } else {
        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Update the password in the admin_tbl
        $sql = "UPDATE admin_tbl SET password='$hashed_password' WHERE email='$email'";
        if(mysqli_query($con, $sql)) {
            // Password updated successfully, trigger SweetAlert
            echo "<script>
                    alert('Password updated successfully');
                    window.location.href = 'Forgotpassword.php';
                 </script>";
            exit(); // Ensure that no further output is sent
        } else {
            echo "Error updating password: " . mysqli_error($con);
        }
    }
}
?>
