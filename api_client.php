<?php

/**
 * api_client.php — petite fonction utilisée par TOUTES les pages HTML
 * du site (login.php, register.php, blogMaker.php, blogViewer.php,
 * DemoniChoice/blog.php, DemoniChoice/articleOn.php...) pour parler à
 * l'API au lieu de toucher la base de données elles-mêmes.
 *
 * C'est exactement le même principe que ce que fera ton launcher Java
 * plus tard : il enverra des requêtes HTTP à ces mêmes routes
 * /API/... Le site web devient donc lui-même "un client de l'API",
 * au même titre que le launcher.
 */

function api_request(string $path, string $method = "GET", array $params = [], bool $internal = false): array
{
    // On appelle l'API en local (127.0.0.1) plutôt que via le nom de
    // domaine public : c'est plus rapide, ça évite un aller-retour
    // inutile sur Internet, et ça continue de marcher même si le
    // domaine public change.
    $port = $_SERVER['SERVER_PORT'] ?? '8080';
    $url = "http://127.0.0.1:{$port}/API/{$path}";

    $ch = curl_init();

    if ($method === "GET") {
        if (!empty($params)) {
            $url .= "?" . http_build_query($params);
        }
        curl_setopt($ch, CURLOPT_URL, $url);
    } else {
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
    }

    $headers = [];
    if ($internal) {
        // Secret partagé : prouve à l'API que cet appel vient bien du
        // serveur lui-même, pas d'un visiteur qui a deviné l'URL.
        $headers[] = "X-Internal-Secret: " . getenv("INTERNAL_API_SECRET");
    }
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    $decoded = json_decode($response ?: '', true);

    if (!is_array($decoded)) {
        error_log("api_request: réponse invalide de $url (HTTP $httpCode) : " . substr((string) $response, 0, 500));
        return ["success" => false, "message" => "api_error"];
    }

    return $decoded;
}
