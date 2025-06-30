<?php
// Establish connection to your database
$servername = "localhost";
$username = "root"; // Your MySQL username
$password = ""; // Your MySQL password
$dbname = "donation"; // Your MySQL database name

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $donorName = isset($_POST['donorName']) ? $_POST['donorName'] : '';
    $corporate = isset($_POST['corporate']) ? $_POST['corporate'] : '';
    $date = isset($_POST['date']) ? $_POST['date'] : '';
    $typeDonation = isset($_POST['typeDonation']) ? $_POST['typeDonation'] : '';
    $cash = isset($_POST['cash']) ? $_POST['cash'] : '';
    $typeInkind = isset($_POST['typeInkind']) ? $_POST['typeInkind'] : '';
    $kindFood = isset($_POST['kindFood']) ? $_POST['kindFood'] : '';
    $howFood = isset($_POST['howFood']) ? $_POST['howFood'] : '';
    $yearExpire = isset($_POST['yearExpire']) ? $_POST['yearExpire'] : '';
    $typeClothes = isset($_POST['typeClothes']) ? $_POST['typeClothes'] : '';
    $howClothes = isset($_POST['howClothes']) ? $_POST['howClothes'] : '';
    $sizeClothes = isset($_POST['sizeClothes']) ? $_POST['sizeClothes'] : '';
    $typePayment = isset($_POST['typePayment']) ? $_POST['typePayment'] : '';
    
    // Check if a file is uploaded
    if(isset($_FILES['photo']) && $_FILES['photo']['size'] > 0) {
        // File upload handling
        $targetDir = "uploads_admin/"; // Directory where uploaded files will be stored
        $targetFile = $targetDir . basename($_FILES["photo"]["name"]); // Path of the uploaded file
        $uploadOk = 1; // Flag to check if the file was uploaded successfully
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION)); // File extension

        // Check if image file is a actual image or fake image
        if (isset($_POST["submit"])) {
            $check = getimagesize($_FILES["photo"]["tmp_name"]);
            if ($check !== false) {
                $uploadOk = 1;
            } else {
                $uploadOk = 0;
            }
        }

        // Check if file already exists
        if (file_exists($targetFile)) {
            $uploadOk = 0;
        }

        // Check file size
        if ($_FILES["photo"]["size"] > 500000) {
            $uploadOk = 0;
        }

        // Allow certain file formats
        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
            && $imageFileType != "gif") {
            $uploadOk = 0;
        }

        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            // If file upload failed, return error message
            $response = array(
                'status' => 'error',
                'message' => 'File upload failed'
            );
            echo json_encode($response);
            exit; // Stop further execution
        } else {
            // If everything is ok, try to upload file
            if (!move_uploaded_file($_FILES["photo"]["tmp_name"], $targetFile)) {
                // If file upload failed, return error message
                $response = array(
                    'status' => 'error',
                    'message' => 'File upload failed'
                );
                echo json_encode($response);
                exit; // Stop further execution
            }
        }
        // Set $photo_path to the path of the uploaded file
        $photo_path = $targetFile;
    } else {
        // If no file uploaded, set $photo_path to NULL or an empty string, depending on your database schema
        $photo_path = NULL; // or $photo_path = '';
    }

    // Establish connection to your database
    $con = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($con->connect_error) {
        die("Connection failed: " . $con->connect_error);
    }

    // Prepare SQL statement to insert data into the database
    $sql = "INSERT INTO donation_tbl (donorName, corporate, date, typeDonation, cash, typeInkind, kindFood, howFood, yearExpire, typeClothes, howClothes, sizeClothes, typePayment, photo)
            VALUES ('$donorName', '$corporate', '$date', '$typeDonation', '$cash', '$typeInkind', '$kindFood', '$howFood', '$yearExpire', '$typeClothes', '$howClothes', '$sizeClothes', '$typePayment', '$photo_path')";

    if ($con->query($sql) === TRUE) {
        // Return success message
        $response = array(
            'status' => 'success',
            'message' => 'Donation successful!'
        );
        echo json_encode($response);
    } else {
        // Return error message
        $response = array(
            'status' => 'error',
            'message' => 'Error: ' . $sql . "<br>" . $con->error
        );
        echo json_encode($response);
    }

    // Close database connection
    $con->close();
}
?>
