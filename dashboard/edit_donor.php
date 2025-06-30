<?php
include '../php/config.php';

$id = $_POST['donateID'];
$name = $_POST['name'];
$email = $_POST['email'];
$phone_number = $_POST['phone_number'];
$address = $_POST['address'];

$sql = "UPDATE donationinfo_tbl SET name='$name', email='$email', phone_number='$phone_number', address='$address' WHERE id=$id";

if ($con->query($sql) === TRUE) {
    echo "Donor updated successfully";
} else {
    echo "Error updating donor: " . $con->error;
}

$con->close();
?>
