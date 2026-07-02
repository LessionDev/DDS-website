<?php

/**
 * AVANT : les identifiants de la base étaient écrits en dur ici, ce qui
 * les a exposés publiquement quand le dépôt est passé en public.
 *
 * MAINTENANT : on les lit depuis des variables d'environnement. Elles ne
 * sont donc JAMAIS présentes dans le code source ni sur GitHub.
 *
 * Comment les définir :
 * - En local : un fichier ".env" chargé par ton serveur, ou les variables
 *   d'environnement de ton système.
 * - Sur Railway : Project > Variables, ajoute DB_HOST, DB_USER, DB_PASS, DB_NAME.
 * - Dans le Dockerfile / docker-compose : "ENV DB_HOST=..." ou "--env-file".
 *
 * Important : après avoir lu ce commentaire, va sur Railway et RÉGÉNÈRE
 * le mot de passe MySQL, l'ancien est compromis car il a été public.
 */

$dbHost = getenv("DB_HOST");
$dbUser = getenv("DB_USER");
$dbPass = getenv("DB_PASS");
$dbName = getenv("DB_NAME");

if (!$dbHost || !$dbUser || !$dbPass || !$dbName) {
    die("Configuration manquante : vérifie les variables d'environnement DB_HOST, DB_USER, DB_PASS, DB_NAME.");
}

$conn = new mysqli($dbHost, $dbUser, $dbPass, $dbName);

if ($conn->connect_error) {
    // On ne montre plus le détail de l'erreur MySQL à l'utilisateur final,
    // ça peut révéler des infos sur ton infra (version, structure...).
    error_log("Erreur DB : " . $conn->connect_error);
    die("Une erreur est survenue, réessaie plus tard.");
}
