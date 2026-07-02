<?php

/**
 * API/posts.php — route de LECTURE des articles de blog.
 * Utilisée par : blog.php, blogViewer.php, DemoniChoice/blog.php,
 * DemoniChoice/articleOn.php, et plus tard le launcher si besoin.
 *
 * C'est une route publique en lecture (les articles de blog sont déjà
 * publics sur le site), donc pas besoin du secret interne ici,
 * contrairement à posts_create.php qui écrit dans la base.
 */

require "db.php";
header("Content-Type: application/json");

if ($_SERVER["REQUEST_METHOD"] !== "GET") {
    http_response_code(405);
    die(json_encode(["success" => false, "message" => "Method not allowed"]));
}

// --- Un seul article, par id (ex: DemoniChoice/articleOn.php) ---
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    $stmt = $conn->prepare("SELECT id, title, content, image, author_id, blogDestination FROM posts WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $post = $stmt->get_result()->fetch_assoc();

    if (!$post) {
        http_response_code(404);
        die(json_encode(["success" => false, "message" => "Not found"]));
    }

    // Article précédent / suivant, pour les boutons de navigation.
    $prevId = $id - 1;
    $nextId = $id + 1;

    $stmt = $conn->prepare("SELECT id FROM posts WHERE id = ?");
    $stmt->bind_param("i", $prevId);
    $stmt->execute();
    $hasPrev = $stmt->get_result()->num_rows > 0;

    $stmt->bind_param("i", $nextId);
    $stmt->execute();
    $hasNext = $stmt->get_result()->num_rows > 0;

    echo json_encode([
        "success" => true,
        "post" => $post,
        "hasPrev" => $hasPrev,
        "prevId" => $prevId,
        "hasNext" => $hasNext,
        "nextId" => $nextId,
    ]);
    exit;
}

// --- Liste d'articles ---
// Soit par "destination" (le blog public, ex: ?destination=demonichoice),
// soit par "author_id" (utilisé par blogViewer.php pour "mes articles" ;
// cette valeur vient TOUJOURS de $_SESSION côté appelant, jamais d'un
// champ que le visiteur peut modifier depuis son navigateur).
$destination = $_GET['destination'] ?? null;
$authorId = $_GET['author_id'] ?? null;

if ($destination !== null) {
    $stmt = $conn->prepare("SELECT id, title, image FROM posts WHERE blogDestination = ? ORDER BY id DESC");
    $stmt->bind_param("s", $destination);
} elseif ($authorId !== null) {
    $authorId = intval($authorId);
    $stmt = $conn->prepare("SELECT id, title, image FROM posts WHERE author_id = ? ORDER BY id DESC");
    $stmt->bind_param("i", $authorId);
} else {
    http_response_code(400);
    die(json_encode(["success" => false, "message" => "Missing destination or author_id"]));
}

$stmt->execute();
$result = $stmt->get_result();
$posts = [];
while ($row = $result->fetch_assoc()) {
    $posts[] = $row;
}

echo json_encode(["success" => true, "posts" => $posts]);
