<?php
/**
 * Empêche les redirections ouvertes ("open redirect").
 *
 * Avant : header("Location: " . $_GET['cameFrom']) redirige vers N'IMPORTE
 * QUELLE URL fournie par l'attaquant (ex: cameFrom=https://site-pirate.com).
 * C'est utilisé pour du phishing : la victime clique un lien qui pointe
 * vers TON site (donc l'URL a l'air légitime), se connecte, puis se
 * retrouve redirigée vers un faux site qui lui vole ses identifiants.
 *
 * Règle : on n'accepte comme "cameFrom" qu'un chemin interne qui commence
 * par un seul "/" (jamais "//" ni "http://" ni "https:").
 */
function safe_redirect_target(?string $location, string $default = "index.php"): string
{
    if (
        $location === null ||
        $location === "" ||
        $location[0] !== "/" ||
        $location[1] !== "/" ||
        $location[2] !== "/" ||
        str_starts_with($location, "//") ||
        preg_match('#^https?:#i', $location)
    ) {
        return $default;
    }

    return $location;
}
