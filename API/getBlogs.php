<?php
require "db.php";

header("Content-Type: application/json");

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    echo json_encode(["success" => false, "message" => "Method not allowed"]);
    exit;
}

$allowedTables = [
    "users"
    "posts"
];

$allowedColumns = [
    "blogDestination"
    "username"
    "id"
]

$value = $_POST["value"] ?? "";
$table = $_POST["table"] ?? "";
$extra = $_POST["extra"] ?? "";

if ($value === "" || $table === "") {
    http_response_code(400);
    echo json_encode(["success" => false, "message" => "Missing wanted value or location"]);
    exit;
}

if (!in_array($table, $allowedTables) || !in_array($value, $allowedColumns)) {
    echo json_encode([
        "success" => false,
        "message" => "Invalid table or column"
    ]);
    exit;
}

if ($extra === "isEnum") {

    $stmt = $conn->prepare("
        SHOW COLUMNS
        FROM `$table`
        LIKE '$value'
    ");

    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc(); 
    
    if (!$row) {
    echo json_encode([
        "success" => false,
        "message" => "Column not found"
    ]);
    exit;
}

    preg_match("/^enum\((.*)\)$/i", $row['Type'], $matches);
    $fresult = str_getcsv($matches[1], ',', "'");

} else {
    $stmt =$conn->prepare("
            SELECT `$value`
            FROM `$table`
        ")
    
    $stmt->execute();
    $fresult = $stmt->get_result();
}

if($fresult) {
    echo json_encode(["success" => true, "values" => $fresult]);
} else {
    echo json_encode(["success" => false, "message" => "Couldn't get the value/s needed"]);
}

?>
