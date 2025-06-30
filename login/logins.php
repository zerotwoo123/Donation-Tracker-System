<?php
session_start();
require '../php/config.php';

$conn = new mysqli('localhost', 'root', '', 'donation');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Ensure email and password are provided
if (!isset($_POST['email']) || !isset($_POST['password'])) {
    $response = array("success" => false, "message" => "Missing email or password");
    echo json_encode($response);
    exit;
}

$email = trim($_POST['email']);
$password = trim($_POST['password']);

if (empty($email) || empty($password)) {
    $response = array("success" => false, "message" => "Email and password cannot be empty");
    echo json_encode($response);
    exit;
}

// Only allow admin login
$table = 'admin_tbl';

// Fetch admin data
$sql = "SELECT * FROM $table WHERE email=?";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    error_log("Prepare failed: " . $conn->error);
    $response = array("success" => false, "message" => "Database error");
    echo json_encode($response);
    exit;
}

$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();

    // Check if the password matches
    if (password_verify($password, $row['password'])) {
        $_SESSION['loggedin'] = true;
        $_SESSION['email'] = $email;
        $_SESSION['name'] = $row['name']; // Store the name in session
        $_SESSION['role'] = $row['role']; // Store role (super_admin/admin)

        // Redirect based on role
        if ($row['role'] == 'super_admin' || $row['role'] == 'admin') {
            error_log("Session Variables: " . print_r($_SESSION, true));
            $response = array("success" => true, "message" => "Login successful", "redirect" => "/dashboard/dashboard.php");
        } else {
            $response = array("success" => false, "message" => "Unauthorized role");
        }
    } else {
        error_log("Password mismatch for user: $email");
        $response = array("success" => false, "message" => "Incorrect password");
    }
} else {
    error_log("Email not found: $email");
    $response = array("success" => false, "message" => "Email not found");
}

$stmt->close();
$conn->close();

header('Content-Type: application/json');
echo json_encode($response);
?>
