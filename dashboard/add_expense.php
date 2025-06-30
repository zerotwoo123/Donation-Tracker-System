<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $description = $_POST["description"];
    $cash = $_POST["cash"];
    $date = $_POST["date"];

    // File upload
    $targetDir = "uploads/"; // Specify the directory where uploaded files will be stored
    $fileName = basename($_FILES["photo"]["name"]);
    $targetFilePath = $targetDir . $fileName;
    $fileType = pathinfo($targetFilePath,PATHINFO_EXTENSION);

    // Create the uploads directory if it doesn't exist
    if (!file_exists($targetDir)) {
        mkdir($targetDir, 0777, true);
    }

    // Allow certain file formats
    $allowTypes = array('jpg','png','jpeg','gif');
    if (in_array($fileType, $allowTypes)) {
        // Upload file to server
        if (move_uploaded_file($_FILES["photo"]["tmp_name"], $targetFilePath)) {
            // Database connection
            $servername = "localhost";
            $username = "root";
            $password = "";
            $dbname = "donation";

            // Create connection
            $conn = new mysqli($servername, $username, $password, $dbname);

            // Check connection
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // Insert expense into database
            $sql = "INSERT INTO expenses_tbl (description, cash, date, photo) VALUES ('$description', '$cash', '$date', '$fileName')";

            if ($conn->query($sql) === TRUE) {
                header('Location: expenses.php');
                exit;
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }

            $conn->close();
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    } else {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
    }
}
?>
