<?php
// AVANT : error_reporting(E_ALL) + display_errors=1 en production ->
// n'importe quelle erreur PHP révèle des chemins serveur, des requêtes
// SQL, parfois des identifiants, à n'importe quel visiteur. Ces réglages
// ne doivent exister que sur un environnement de développement local,
// jamais sur le serveur exposé publiquement.
session_start();
require "config.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

// AVANT : $id = $_GET['id'] venait de l'URL -> n'importe qui pouvait
// lister les articles de N'IMPORTE QUEL auteur en changeant l'ID, et la
// requête était vulnérable à l'injection SQL. On affiche maintenant
// uniquement les articles de l'utilisateur connecté, via une requête préparée.
$id = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT * FROM posts WHERE author_id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
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
        <link rel="icon" type="image/png" sizes="16x16" href="../style/res/Logo16.png">
        <link rel="shortcut icon" href="style/res/Logo.ico">
        <link rel="icon" type="image/png" sizes="32x32" href="style/res/Logo.png">
        <link rel="apple-touch-icon" sizes="180x180" href="style/res/Logo180.png">
        <link rel="apple-touch-icon" sizes="167x167" href="style/res/Logo167.png">
        <link rel="apple-touch-icon" sizes="152x152" href="style/res/Logo152.png">
    </head>
    <body>
        <?php
            while ($row = $result->fetch_assoc()) {
        ?>
        <div class="container">
            <div class="title">
                <!-- AVANT : le titre était affiché sans échappement -> un titre
                     d'article malveillant s'exécute chez chaque visiteur (XSS stocké). -->
                <h2 class="articleLink"><?php echo htmlspecialchars($row['title']); ?></h2>
            </div>
        </div>
        <?php
        }
        ?>     
    </body>
    <footer>
    </footer>
</html>
