<?php
require_once "API/db.php";

header("Content-Type: application/json");

$stmt = $conn->prepare("
            SHOW COLUMNS
            FROM posts
            LIKE 'blogDestination'
            ");

$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc(); 

preg_match("/^enum\((.*)\)$/", $row['Type'], $matches);
$blogs = str_getcsv($matches[1], ',', "'");

echo json_encode(["success" => true, "blogDestination" => $blogs]);
?>
