<?php
require '../php/config.php';
$conn = new mysqli('localhost', 'root', '', 'donation');

// Check connection
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

// Initialize response array
$response = array();

// Process form data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $name = $_POST["name"];
    $age = $_POST["age"];
    $gender = $_POST["gender"];
    $phone = $_POST["phoneNumber"];
    $email = $_POST["email"];
    $address = $_POST["address"];
    
    // Check if required fields are empty
    if (empty($name) || empty($age) || empty($gender) || empty($phone) || empty($email) || empty($address)) {
        // Set error message in the response
        $response['success'] = false;
        $response['message'] = 'All fields are required';
    } else {
        // Prepare SQL statement to insert data into the database
        $sql = "INSERT INTO donationinfo_tbl (name, age, gender, phone, email, address)
                VALUES ('$name', '$age', '$gender', '$phone', '$email', '$address')";
        
        // Execute SQL statement
        if (mysqli_query($con, $sql)) {
            // Set success message in the response
            $response['success'] = true;
            $response['message'] = 'Donor added successfully';
        } else {
            // Set error message in the response
            $response['success'] = false;
            $response['message'] = 'Failed to add donor: ' . mysqli_error($con);
        }
    }
} else {
    // Set error message in the response if request method is not POST
    $response['success'] = false;
    $response['message'] = 'Invalid request method';
}

// Close connection
mysqli_close($con);

// Return JSON response
echo json_encode($response);
?>
