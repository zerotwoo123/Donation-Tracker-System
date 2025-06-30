<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: ../login/login.php");
    exit;
}

if (isset($_POST['expID']) && isset($_POST['description']) && isset($_POST['cash']) && isset($_POST['date'])) {
    $expID = $_POST['expID'];
    $description = $_POST['description'];
    $cash = $_POST['cash'];
    $date = $_POST['date'];

    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "donation";

    $con = new mysqli($servername, $username, $password, $dbname);

    if ($con->connect_error) {
        die("Connection failed: " . $con->connect_error);
    }

    $sql = "UPDATE expenses_tbl SET description = ?, cash = ?, date = ? WHERE expID = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("sssi", $description, $cash, $date, $expID);

    if ($stmt->execute()) {
        header("Location: expenses.php");
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $con->close();
} else {
    echo "Invalid input.";
}
?>
