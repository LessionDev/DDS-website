FROM php:8.2-cli
WORKDIR /app
COPY . /app
RUN docker-php-ext-install mysqli pdo pdo_mysql
# AVANT : le serveur intégré de PHP ("php -S") ne traite qu'UNE seule
# requête à la fois par défaut. Or maintenant, login.php/register.php
# appellent l'API en HTTP pendant qu'ils traitent déjà une requête :
# le serveur se retrouve à devoir répondre à une deuxième requête
# alors qu'il est occupé avec la première -> blocage jusqu'au timeout
# -> l'appel à l'API échoue -> messages d'erreur affichés à tort
# ("Credentials don't match", "réessaie plus tard") même avec les
# bons identifiants.
#
# MAINTENANT : on autorise plusieurs "workers" (processus PHP) en
# parallèle, pour que le serveur puisse traiter la requête du visiteur
# ET l'appel interne vers l'API en même temps.
ENV PHP_CLI_SERVER_WORKERS=4
CMD ["php", "-S", "0.0.0.0:8080", "-t", "/app"]
