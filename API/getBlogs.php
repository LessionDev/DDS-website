<?php
require_once "API/db.php";

header("Content-Type: application/json");

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    echo json_encode(["success" => false, "message" => "Method not allowed"]);
    exit;
}

$username = $_POST["blogDestination"] ?? "";
$password = $_POST["password"] ?? "";

if ($username === "" || $password === "") {
    http_response_code(400);
    echo json_encode(["success" => false, "message" => "Missing credentials"]);
    exit;
}

$stmt = $conn->prepare("
            SHOW COLUMNS
            FROM posts
            LIKE 'blogDestination'
            ");

$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc(); 

preg_match("/^enum\((.*)\)$/i", $row['Type'], $matches);
$blogs = str_getcsv($matches[1], ',', "'");

echo json_encode(["success" => true, "blogDestination" => $blogs]);
?>
