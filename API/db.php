<?php

/**
 * API/db.php — LE SEUL fichier de tout le site qui ouvre une connexion
 * à la base de données. Plus aucun autre fichier ne doit faire
 * `new mysqli(...)` ni connaître DB_HOST / DB_USER / DB_PASS / DB_NAME.
 *
 * Pourquoi ce changement :
 * - AVANT : 7-8 fichiers différents (index.php, login.php, register.php,
 *   blogMaker.php, blogViewer.php, DemoniChoice/blog.php,
 *   DemoniChoice/articleOn.php...) incluaient chacun config.php et
 *   lançaient leurs propres requêtes SQL. Plus il y a d'endroits qui
 *   touchent la base, plus il y a de chances d'oublier une requête
 *   préparée ou un htmlspecialchars() quelque part (ce qui est arrivé).
 * - MAINTENANT : toute la logique SQL vit uniquement dans le dossier
 *   API/. Le reste du site (les pages HTML) ne fait plus que des
 *   appels HTTP vers cette API — exactement comme le fera ton
 *   launcher Java plus tard. Un seul endroit à sécuriser et à
 *   auditer, et le site web + le launcher utilisent toujours
 *   EXACTEMENT la même logique métier (pas de code dupliqué qui finit
 *   par diverger).
 */

$dbHost = getenv("DB_HOST");
$dbUser = getenv("DB_USER");
$dbPass = getenv("DB_PASS");
$dbName = getenv("DB_NAME");

$missing = [];
if (!$dbHost) $missing[] = "DB_HOST";
if (!$dbUser) $missing[] = "DB_USER";
if (!$dbPass) $missing[] = "DB_PASS";
if (!$dbName) $missing[] = "DB_NAME";

if (!empty($missing)) {
    error_log("Variables d'environnement manquantes : " . implode(", ", $missing));
    http_response_code(500);
    header("Content-Type: application/json");
    die(json_encode(["success" => false, "message" => "Configuration manquante côté serveur."]));
}

$conn = new mysqli($dbHost, $dbUser, $dbPass, $dbName);

if ($conn->connect_error) {
    // On ne montre jamais le détail de l'erreur MySQL à un client (site
    // ou launcher), ça peut révéler des infos sur l'infra.
    error_log("Erreur DB : " . $conn->connect_error);
    http_response_code(500);
    header("Content-Type: application/json");
    die(json_encode(["success" => false, "message" => "Une erreur est survenue, réessaie plus tard."]));
}
