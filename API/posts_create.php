<?php

/**
 * API/posts_create.php — route d'ÉCRITURE : crée un nouvel article.
 * Appelée uniquement par blogMaker.php (via api_client.php), jamais
 * directement par un navigateur.
 *
 * Le fichier image est déjà géré (validé + enregistré sur le disque)
 * par blogMaker.php avant l'appel : cette route ne reçoit que le NOM
 * du fichier déjà écrit, pas le fichier lui-même. Elle ne fait
 * qu'insérer la ligne correspondante dans la table "posts".
 */

require "db.php";
require "_internal_guard.php";

header("Content-Type: application/json");

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    die(json_encode(["success" => false, "message" => "Method not allowed"]));
}

require_internal_secret();

$title = trim($_POST['title'] ?? '');
$content = trim($_POST['content'] ?? '');
$destination = trim($_POST['destination'] ?? '');
$authorId = intval($_POST['author_id'] ?? 0);
$imageName = $_POST['image'] ?? null;

if ($title === '' || $content === '' || $destination === '' || $authorId <= 0) {
    http_response_code(400);
    die(json_encode(["success" => false, "message" => "Missing fields"]));
}

// Requête préparée : les valeurs ne sont jamais concaténées dans le SQL.
$stmt = $conn->prepare(
    "INSERT INTO posts (title, content, author_id, image, blogDestination) VALUES (?, ?, ?, ?, ?)"
);
$stmt->bind_param("ssiss", $title, $content, $authorId, $imageName, $destination);

if ($stmt->execute()) {
    echo json_encode(["success" => true, "id" => $stmt->insert_id]);
} else {
    error_log("posts_create insert failed: " . $stmt->error);
    http_response_code(500);
    echo json_encode(["success" => false, "message" => "Insert failed"]);
}
