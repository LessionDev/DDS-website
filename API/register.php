<?php

/**
 * API/register.php — route d'ÉCRITURE : crée un compte utilisateur.
 * Appelée par register.php (le formulaire) via api_client.php, et
 * pourra plus tard être appelée directement par le launcher pour
 * permettre la création de compte "DDS" depuis l'application.
 *
 * Route publique volontairement (comme API/login.php) : c'est le
 * point d'entrée normal pour s'inscrire, que ce soit depuis le site
 * ou depuis le launcher. Pas besoin du secret interne ici.
 */

require "db.php";
header("Content-Type: application/json");

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    die(json_encode(["success" => false, "message" => "Method not allowed"]));
}

$username = trim($_POST["username"] ?? "");
$password = $_POST["password"] ?? "";

// Mêmes règles de validation qu'avant (voir l'explication d'origine
// dans le premier lot de correctifs) : on les garde ici car c'est
// maintenant le SEUL endroit où un compte est créé.
if (!preg_match('/^[a-zA-Z0-9_-]{3,20}$/', $username)) {
    http_response_code(400);
    die(json_encode(["success" => false, "message" => "invalid_username"]));
}

if (strlen($password) < 8) {
    http_response_code(400);
    die(json_encode(["success" => false, "message" => "weak_password"]));
}

$stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();

if ($stmt->get_result()->num_rows > 0) {
    echo json_encode(["success" => false, "message" => "username_taken"]);
    exit;
}

$hashedPassword = password_hash($password, PASSWORD_DEFAULT);
$stmt = $conn->prepare("INSERT INTO users (username, password, status) VALUES (?, ?, 'member')");
$stmt->bind_param("ss", $username, $hashedPassword);

if ($stmt->execute()) {
    echo json_encode(["success" => true]);
} else {
    error_log("register insert failed: " . $stmt->error);
    http_response_code(500);
    echo json_encode(["success" => false, "message" => "server_error"]);
}
