<?php


$conn = new mysqli(
    "sql208.infinityfree.com",   // HOST
    "if0_41755681",              // USERNAME
    "DemoniChoiceWeb",          // PASSWORD
    "if0_41755681_demonichoicedb"        // DATABASE NAME
);


if ($conn->connect_error) {
    die("Erreur DB : " . $conn->connect_error);
}
?>