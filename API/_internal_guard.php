<?php

/**
 * API/_internal_guard.php — protège les routes d'ÉCRITURE de l'API
 * (par exemple "créer un article") contre n'importe qui sur Internet.
 *
 * Comment ça marche :
 * Le site web (via api_client.php) et, plus tard, ton launcher Java,
 * envoient un header "X-Internal-Secret" dont la valeur doit
 * correspondre exactement à la variable d'environnement
 * INTERNAL_API_SECRET définie sur Railway. Sans ce secret, la requête
 * est rejetée (401 Unauthorized).
 *
 * Important : ce n'est PAS un remplacement de la vérification "est-ce
 * que CET utilisateur a le droit de faire ça" — ça, c'est déjà vérifié
 * par la page appelante (ex : blogMaker.php regarde $_SESSION avant
 * même d'appeler l'API). Ce secret sert juste à empêcher un inconnu
 * d'appeler direct l'endpoint d'écriture depuis son navigateur ou avec
 * curl en devinant l'URL /API/posts_create.php.
 */

function require_internal_secret(): void
{
    $expected = getenv("INTERNAL_API_SECRET");
    $given = $_SERVER['HTTP_X_INTERNAL_SECRET'] ?? '';

    // hash_equals() compare en temps constant, pour éviter qu'un
    // attaquant devine le secret caractère par caractère en mesurant
    // le temps de réponse ("timing attack").
    if (!$expected || !hash_equals($expected, $given)) {
        http_response_code(401);
        header("Content-Type: application/json");
        die(json_encode(["success" => false, "message" => "Unauthorized"]));
    }
}
