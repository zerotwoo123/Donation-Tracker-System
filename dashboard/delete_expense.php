<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    echo 'error';
    exit;
}

if (isset($_POST['expID'])) {
    $expID = $_POST['expID'];

    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "donation";

    $con = new mysqli($servername, $username, $password, $dbname);

    if ($con->connect_error) {
        die("Connection failed: " . $con->connect_error);
    }

    $sql = "DELETE FROM expenses_tbl WHERE expID = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("i", $expID);

    if ($stmt->execute()) {
        echo 'success';
    } else {
        echo 'error';
    }

    $stmt->close();
    $con->close();
} else {
    echo 'error';
}
?>
