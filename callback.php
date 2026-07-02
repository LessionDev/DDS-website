<?php
// AVANT : ce fichier ouvrait une connexion à la base ("require
// config.php") alors qu'il ne fait rien avec ($conn n'est jamais
// utilisé) — probablement les débuts d'une intégration OAuth
// (Microsoft/Discord ?) restée inachevée.
//
// Attention : tel quel, ce fichier est dangereux à laisser en ligne :
// il affiche "Made Connection (step 1)" pour n'importe quel visiteur,
// ce qui suggère un flux d'authentification à moitié écrit. Tant
// qu'il n'est pas terminé, mieux vaut le désactiver plutôt que de le
// laisser accessible publiquement avec un comportement incomplet.
http_response_code(404);
exit;
