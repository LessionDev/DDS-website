<?php


$conn = new mysqli(
    "hayabusa.proxy.rlwy.net:47552",   // HOST
    "root",              // USERNAME
    "ZMLMjPaLRqKtdZpefRaNwVKDYSgzKMWW",          // PASSWORD
    "railway"        // DATABASE NAME
);


if ($conn->connect_error) {
    die("Erreur DB : " . $conn->connect_error);
}
?>
