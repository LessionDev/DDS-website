<?php   
$location = $_GET['cameFrom'];
session_start();
session_destroy();
header("Location: $location");

?>