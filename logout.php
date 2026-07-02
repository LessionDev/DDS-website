<?php
require "safe_redirect.php";

session_start();

$location = safe_redirect_target($_GET['cameFrom'] ?? null);

// AVANT : session_destroy() seul laisse les données de $_SESSION en mémoire
// pour le reste du script, et ne vide pas le tableau superglobal.
// La bonne pratique est de vider $_SESSION puis de détruire la session,
// et d'expirer aussi le cookie de session côté navigateur.
$_SESSION = [];

if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

session_destroy();

header("Location: $location");
exit;
