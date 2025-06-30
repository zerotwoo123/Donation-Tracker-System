<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: ../login/login.php"); // Redirect to login page if not logged in
    exit;
}

require '../php/config.php';
$conn = new mysqli('localhost', 'root', '', 'donation');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function logActivity($conn, $adminID, $action, $affected, $details)
{
    $time = date('Y-m-d H:i:s');
    $stmt = $conn->prepare("INSERT INTO activity_log (adminsID, time, action, affected, details) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("issss", $adminID, $time, $action, $affected, $details);
    $stmt->execute();
    $stmt->close();
}

// Process different actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $adminID = $_SESSION['adminID']; // Assuming adminID is stored in session

    // Action type
    $actionType = $_POST['actionType']; // actionType will help determine the specific action

    switch ($actionType) {
        case 'addDonor':
            $name = ["name"];
            $age = ["age"];
            $gender = ["gender"];
            $phone = ["phoneNumber"];
            $email = ["email"];
            $address = ["address"];
            $stmt = $conn->prepare("INSERT INTO donationinfo_tbl (name, age, gender, phone, email, address) VALUES (?, ?)");
            $stmt->bind_param("ss", $name, $age, $gender, $phone, $email, $address);

            if ($stmt->execute()) {
                logActivity($conn, $adminID, 'Added Donor', 'Donor', 'Added donor with email ' . $donorEmail);
                echo "Donor added successfully.";
            } else {
                echo "Error: " . $stmt->error;
            }
            $stmt->close();
            break;

            case 'editDonor':
                $donorID = $_POST['donorID'];
                $name = $_POST['name'];
                $age = $_POST['age'];
                $gender = $_POST['gender'];
                $phone = $_POST['phone'];
                $email = $_POST['email'];
                $address = $_POST['address'];
                
                // Additional donor fields...
            
                $stmt = $conn->prepare("UPDATE donationinfo_tbl SET name = ?, age = ?, gender = ?, phone = ?, email = ?, address = ? WHERE donorID = ?");
                $stmt->bind_param("ssssssi", $name, $age, $gender, $phone, $email, $address, $donorID);
            
                if ($stmt->execute()) {
                    logActivity($conn, $adminID, 'Edited Donor', 'Donor', 'Edited donor ID ' . $donorID);
                    echo "Donor updated successfully.";
                } else {
                    echo "Error: " . $stmt->error;
                }
                $stmt->close();
                break;

        case 'deleteDonor':
            $donorID = $_POST['donorID'];

            $stmt = $conn->prepare("DELETE FROM donationinfo_tbl WHERE donorID = ?");
            $stmt->bind_param("i", $donorID);

            if ($stmt->execute()) {
                logActivity($conn, $adminID, 'Deleted Donor', 'Donor', 'Deleted donor ID ' . $donorID);
                echo "Donor deleted successfully.";
            } else {
                echo "Error: " . $stmt->error;
            }
            $stmt->close();
            break;

        case 'assignRole':
            $adminIDToUpdate = $_POST['adminID'];
            $newRole = $_POST['newRole'];

            $stmt = $conn->prepare("UPDATE admin_tbl SET role = ? WHERE adminID = ?");
            $stmt->bind_param("si", $newRole, $adminIDToUpdate);

            if ($stmt->execute()) {
                logActivity($conn, $adminID, 'Assigned Role', 'Admin', 'Assigned role ' . $newRole . ' to admin ID ' . $adminIDToUpdate);
                echo "Role assigned successfully.";
            } else {
                echo "Error: " . $stmt->error;
            }
            $stmt->close();
            break;

        case 'addDonation':
            $donorID = $_POST['donorID'];
            $amount = $_POST['amount'];
            $date = $_POST['date'];

            $stmt = $conn->prepare("INSERT INTO donation_tbl (donorID, amount, date) VALUES (?, ?, ?)");
            $stmt->bind_param("ids", $donorID, $amount, $date);

            if ($stmt->execute()) {
                logActivity($conn, $adminID, 'Added Donation', 'Donation', 'Added donation for donor ID ' . $donorID);
                echo "Donation added successfully.";
            } else {
                echo "Error: " . $stmt->error;
            }
            $stmt->close();
            break;

        default:
            echo "Invalid action type.";
            break;
    }
}

$conn->close();
