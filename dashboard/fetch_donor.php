<?php
require '../php/config.php';

// Establish database connection
$conn = new mysqli('localhost', 'root', '', 'donation');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to fetch donations
function fetchDonations($donorName, $conn) {
    $donations = array();

    // Query donation_tbl
    $sql1 = "SELECT * FROM donation_tbl WHERE donorName = ?";
    $stmt1 = $conn->prepare($sql1);
    if ($stmt1) {
        $stmt1->bind_param("s", $donorName);
        $stmt1->execute();
        $result1 = $stmt1->get_result();

        while($row = $result1->fetch_assoc()) {
            $donations[] = array(
                'date' => $row['date'],
                'typeDonation' => $row['typeDonation'],
                'cash' => $row['cash'],
                'typeInkind' => $row['typeInkind'],
                'howFood' => $row['howFood'], // Assuming howFood holds the count of food items
                'clothesCount' => $row['howClothes'], // Assuming howClothes holds the count of clothes items
                'corporateName' => $row['corporate'], // Assuming 'corporate' is the column name for the corporate name
                'photo' => $row['photo'], // Fetch the photo URL
                'typePayment' => $row['typePayment'] // Add typePayment field
            );
        }
        $stmt1->close();
    } else {
        // Log any SQL errors
        error_log("Prepare failed: (" . $conn->errno . ") " . $conn->error);
    }

    return $donations;
}

// Check if donorName is received via POST request
if(isset($_POST['donorName'])) {
    $donorName = $_POST['donorName'];


    // Fetch donations for the provided donorName
    $donations = fetchDonations($donorName, $conn);

    // Send JSON response
    if(!empty($donations)) {
        echo json_encode(array('status' => 'success', 'data' => $donations));
    } else {
        echo json_encode(array('status' => 'error', 'message' => 'No donations found for this donor'));
    }
} else {
    echo json_encode(array('status' => 'error', 'message' => 'donorName not provided'));
}

// Close the database connection
$conn->close();
?>
