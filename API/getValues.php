<?php
require "db.php";

header("Content-Type: application/json");

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    echo json_encode(["success" => false, "message" => "Method not allowed"]);
    exit;
}

$allowedTables = [
    "users",
    "posts"
];

$allowedColumns = [
    "blogDestination",
    "username",
    "id",
    "image",
    "title",
    "content",
    "author_id"
];

$value = $_POST["value"] ?? "";
$table = $_POST["table"] ?? "";
$extra = $_POST["extra"] ?? "";

if ($value === "" || $table === "") {
    http_response_code(400);
    echo json_encode(["success" => false, "message" => "Missing wanted value or location"]);
    exit;
}

if (!in_array($table, $allowedTables) || ( !in_array($value, $allowedColumns) && filter_var($value, FILTER_VALIDATE_INT) === false)) {
    http_response_code(400);
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
    
    if (!isset($matches[1])) {
        echo json_encode([
            "success" => false,
            "message" => "Column is not an ENUM"
        ]);
        exit;
    }
    
    $fresult = str_getcsv($matches[1], ',', "'");

} elseif($extra === "getPostById") {

    $stmt = $conn->prepare("SELECT id, title, content, image, author_id, blogDestination FROM posts WHERE id = ?");
    $stmt->bind_param("i", $value);
    $stmt->execute();
    $post = $stmt->get_result()->fetch_assoc();

    if (!$post) {
        http_response_code(404);
        die(json_encode(["success" => false, "message" => "Not found"]));
    }

    $prevId = $value - 1;
    $nextId = $value + 1;

    $stmt = $conn->prepare("SELECT id FROM posts WHERE id = ?");
    $stmt->bind_param("i", $prevId);
    $stmt->execute();
    $hasPrev = $stmt->get_result()->num_rows > 0;

    $stmt->bind_param("i", $nextId);
    $stmt->execute();
    $hasNext = $stmt->get_result()->num_rows > 0;

    $fresult = [
        "success" => true,
        "post" => $post,
        "hasPrev" => $hasPrev,
        "prevId" => $prevId,
        "hasNext" => $hasNext,
        "nextId" => $nextId,
    ];

} elseif ($extra === "getPostsByAuthorId") {

    if ($value === "") {
        http_response_code(400);
        echo json_encode([
            "success" => false,
            "message" => "Missing author_id"
        ]);
        exit;
    }

    $authorId = intval($value);
    $stmt = $conn->prepare("
        SELECT id, title, image
        FROM posts
        WHERE author_id = ?
        ORDER BY id DESC
    ");

    $stmt->bind_param("i", $authorId);
    $stmt->execute();
    $result = $stmt->get_result();
    $fresult = [];

    while ($row = $result->fetch_assoc()) {
        $fresult[] = $row;
    }
    
} else {

    $stmt = $conn->prepare("
        SELECT `$value`
        FROM `$table`
    ");

    $stmt->execute();
    $result = $stmt->get_result();
    $fresult = [];

    while ($row = $result->fetch_assoc()) {
        $fresult[] = $row[$value];
    }
}

if (isset($fresult)) {
    echo json_encode([
        "success" => true,
        "values" => $fresult
    ]);
} else {
    echo json_encode([
        "success" => false,
        "message" => "Couldn't get the value/s needed"
    ]);
}

?>
