<?php
require "../config.php";

header("Content-Type: application/json");

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    echo json_encode([
        "success" => false,
        "message" => "Method not allowed"
    ]);
    exit;
}

$username = $_POST["username"] ?? "";
$password = $_POST["password"] ?? "";

$stmt = $conn->prepare("SELECT id, username, password, status FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();

$user = $stmt->get_result()->fetch_assoc();

if ($user && password_verify($password, $user["password"])) {

    echo json_encode([
        "success" => true,
        "id" => $user["id"],
        "username" => $user["username"],
        "status" => $user["status"]
    ]);

} else {

    echo json_encode([
        "success" => false,
        "message" => "Invalid username or password"
    ]);

}