<?php
require '../php/config.php';

session_start();
$_SESSION = array();
session_destroy();
header("Location: ../login/login.php");
exit;
?>