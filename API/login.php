<?php
require "db.php";

header("Content-Type: application/json");

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    echo json_encode(["success" => false, "message" => "Method not allowed"]);
    exit;
}

$username = $_POST["username"] ?? "";
$password = $_POST["password"] ?? "";

if ($username === "" || $password === "") {
    http_response_code(400);
    echo json_encode(["success" => false, "message" => "Missing credentials"]);
    exit;
}

/**
 * AVANT : aucune limite de tentatives -> un attaquant peut essayer des
 * milliers de mots de passe par seconde sur n'importe quel compte
 * (brute-force / credential stuffing), directement contre ta base.
 *
 * On ajoute un verrouillage simple par IP + nom d'utilisateur : après
 * 5 échecs en 15 minutes, on bloque temporairement.
 *
 * Nécessite cette table (à créer une fois) :
 *
 * CREATE TABLE login_attempts (
 *   id INT AUTO_INCREMENT PRIMARY KEY,
 *   identifier VARCHAR(191) NOT NULL,   -- ip + ":" + username
 *   created_at DATETIME NOT NULL,
 *   INDEX (identifier, created_at)
 * );
 */

const MAX_ATTEMPTS = 5;
const WINDOW_MINUTES = 15;

$ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
$identifier = $ip . ":" . strtolower($username);

$windowMinutes = WINDOW_MINUTES;
$stmt = $conn->prepare(
    "SELECT COUNT(*) AS attempts FROM login_attempts
     WHERE identifier = ? AND created_at > (NOW() - INTERVAL ? MINUTE)"
);
$stmt->bind_param("si", $identifier, $windowMinutes);
$stmt->execute();
$attempts = (int) $stmt->get_result()->fetch_assoc()["attempts"];

if ($attempts >= MAX_ATTEMPTS) {
    http_response_code(429);
    echo json_encode([
        "success" => false,
        "message" => "Too many attempts, try again later."
    ]);
    exit;
}

$stmt = $conn->prepare("SELECT id, username, password, status FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

if ($user && password_verify($password, $user["password"])) {

    // Connexion réussie : on nettoie les anciennes tentatives pour cet identifiant.
    $clear = $conn->prepare("DELETE FROM login_attempts WHERE identifier = ?");
    $clear->bind_param("s", $identifier);
    $clear->execute();

    echo json_encode([
        "success" => true,
        "id" => $user["id"],
        "username" => $user["username"],
        "status" => $user["status"]
    ]);

} else {

    // Échec : on enregistre la tentative avant de répondre.
    $log = $conn->prepare("INSERT INTO login_attempts (identifier, created_at) VALUES (?, NOW())");
    $log->bind_param("s", $identifier);
    $log->execute();

    echo json_encode([
        "success" => false,
        "message" => "Invalid username or password"
    ]);
}
