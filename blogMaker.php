<?php
session_start();
// AVANT : cette page ouvrait la base et faisait elle-même le
// "INSERT INTO posts". MAINTENANT : elle continue de gérer l'upload
// du fichier (ça, ça reste local au serveur qui reçoit le fichier),
// mais l'enregistrement en base passe par l'API (API/posts_create.php),
// avec un secret interne pour prouver que l'appel vient bien du site.
require "api_client.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit; // AVANT : pas de exit -> le script continuait même sans session.
}

if ($_SESSION['user_status'] !== "author" && $_SESSION['user_status'] !== "admin") {
    header("Location: index.php");
    exit; // AVANT : pareil ici -> un simple membre pouvait quand même publier un article.
}

// AVANT : $author = $_GET['author'] venait de l'URL -> n'importe quel
// auteur pouvait publier un article au nom de QUELQU'UN D'AUTRE en
// changeant juste "?author=12" dans le lien. L'auteur doit TOUJOURS venir
// de la session, jamais d'une valeur fournie par le visiteur.
$author_id = $_SESSION['user_id'];

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $title = trim($_POST['title'] ?? '');
    $content = trim($_POST['content'] ?? '');
    $destinationRaw = trim($_POST['destination'] ?? '');

    if (empty($title) || empty($content) || empty($destinationRaw)) {
        die("Please fill the necessary informations.");
    }

    // AVANT : $destination allait tel quel dans un chemin de fichier
    // ("../style/res/blog/posts/$destination/"). Une valeur comme
    // "../../../../var/www/html" (path traversal) permettait d'écrire
    // des fichiers n'importe où sur le serveur. On restreint maintenant
    // à un nom de dossier simple (lettres/chiffres/-/_).
    if (!preg_match('/^[a-zA-Z0-9_-]{1,50}$/', $destinationRaw)) {
        die("Invalid destination.");
    }
    $destination = $destinationRaw;

    $imageName = null;

    if (!empty($_FILES['image']['name'])) {
        $tmpPath = $_FILES['image']['tmp_name'];
        $originalName = $_FILES['image']['name'];

        // AVANT : aucune vérification -> on pouvait envoyer un fichier
        // "shell.php" à la place d'une image. S'il atterrit dans un dossier
        // servi par le web, l'attaquant obtient l'exécution de code sur
        // TON serveur. On vérifie maintenant :
        // 1) le vrai type MIME du contenu (pas juste l'extension du nom,
        //    qui est fournie par le client et donc falsifiable),
        // 2) qu'il s'agit bien d'une image.
        $allowedMime = [
            'image/jpeg' => 'jpg',
            'image/png'  => 'png',
            'image/gif'  => 'gif',
            'image/webp' => 'webp',
        ];

        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mime = $finfo->file($tmpPath);

        if (!isset($allowedMime[$mime])) {
            die("Only image files (jpg, png, gif, webp) are allowed.");
        }

        // AVANT : le fichier gardait son nom d'origine, fourni par le
        // client -> collisions, ou nom malicieux (ex: "../../x.php").
        // On génère un nom aléatoire avec la bonne extension.
        $imageName = bin2hex(random_bytes(16)) . '.' . $allowedMime[$mime];

        $destinationDir = realpath(__DIR__ . '/style/res/blog/posts') . '/' . $destination;
        if (!is_dir($destinationDir)) {
            mkdir($destinationDir, 0755, true);
        }

        move_uploaded_file($tmpPath, $destinationDir . '/' . $imageName);
    }

    // AVANT : "INSERT INTO posts" construit par concaténation de
    // chaînes ici même -> injection SQL possible via title/content.
    // MAINTENANT : on demande à l'API de créer l'article. Le "true"
    // final envoie le secret interne (X-Internal-Secret) pour prouver
    // que l'appel vient du serveur du site et pas d'un inconnu qui
    // appellerait /API/posts_create.php directement.
    $result = api_request("posts_create.php", "POST", [
        "title" => $title,
        "content" => $content,
        "destination" => $destination,
        "author_id" => $author_id,
        "image" => $imageName,
    ], true);

    if (!empty($result["success"])) {
        $message = "Article posted successfully";
    } else {
        error_log("blogMaker: API insert failed: " . json_encode($result));
        $message = "Something went wrong, please try again.";
    }
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>DemoniChoice</title>
        <link rel="stylesheet" href="style/blog.css" > 
        <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">    
        <link rel="manifest" href="site.webmanifest">
        <link rel="icon" type="image/png" sizes="32x32" href="style/res/Logo32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="style/res/Logo16.png">
        <link rel="shortcut icon" href="style/res/Logo.ico">
        <link rel="icon" type="image/png" sizes="32x32" href="style/res/Logo.png">
        <link rel="apple-touch-icon" sizes="180x180" href="style/res/Logo180.png">
        <link rel="apple-touch-icon" sizes="167x167" href="style/res/Logo167.png">
        <link rel="apple-touch-icon" sizes="152x152" href="style/res/Logo152.png">
    </head>
    <body>
        <?php if ($message): ?><p><?= htmlspecialchars($message) ?></p><?php endif; ?>
        <form method="post" enctype="multipart/form-data">
            <input type="text" name="title" placeholder="The title of your article">
            <textarea name="content" placeholder="Type your article here"></textarea>
            <input type="text" name="destination" placeholder="The name of the Blog you want to post your article">
            <input type="file" name="image">
            <input type="submit" name="submit" value="submit your article">
        </form>   
        <a href="dashboard.php">Back to the dashboard</a>
    </body>
    <footer>
    </footer>
</html>
