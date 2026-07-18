<?php
require_once "API/db.php";

header("Content-Type: application/json");

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    echo json_encode(["success" => false, "message" => "Method not allowed"]);
    exit;
}

$value = $_POST["value"] ?? "";
$table = $_POST["table"] ?? "";
$extra = $_POST["extra"] ?? "";

if ($value === "" || $table === "") {
    http_response_code(400);
    echo json_encode(["success" => false, "message" => "Missing wanted value or location"]);
    exit;
}

if ($extra === "isEnum") {

    $stmt = $conn->prepare("
            SHOW COLUMNS
            FROM ??
            LIKE ??
            ");

    $stmt->bind_param("ss", $table, $value);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc(); 

    preg_match("/^enum\((.*)\)$/i", $row['Type'], $matches);
    $fresult = str_getcsv($matches[1], ',', "'");

} else {
    $stmt =$conn->prepare("
            FROM ??
            
        ")
}

if($fresult) {
    echo json_encode(["success" => true, "value/s" => $fresult]);
} else {
    echo json_encode(["success" => false, "message" => "Couldn't get the value/s needed"]);
}

?>
