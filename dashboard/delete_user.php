<?php
session_start();

// Check if user is logged in, redirect to login page if not
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: ../login/login.php"); // Redirect to login page if not logged in
    exit;
}

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "donation";

// Create connection
$con = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

// Delete donation
if (isset($_POST['userID'])) {
    $userID = $_POST['userID'];

    $sql = "DELETE FROM user_tbl WHERE userID = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("i", $userID);

    if ($stmt->execute()) {
        $response = array('success' => true);
        echo json_encode($response);
        exit;
    } else {
        $response = array('success' => false);
        echo json_encode($response);
        exit;
    }
}

// Fetch donations
$sql = "SELECT userID, name, age, gender, phone, email, address FROM user_tbl";
$result = $con->query($sql);

if ($result->num_rows > 0) {
    // Output data of each row
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row["name"] . "</td>";
        echo "<td>" . $row["age"] . "</td>";
        echo "<td>" . $row["gender"] . "</td>";
        echo "<td>" . $row["phone"] . "</td>";
        echo "<td>" . $row["email"] . "</td>";
        echo "<td>" . $row["address"] . "</td>";
        echo "<td><button class='btn btn-danger deleteUserBtn' data-id='" . $row["userID"] . "'>Delete</button></td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='7'>No results found</td></tr>";
}

$con->close();
?>
