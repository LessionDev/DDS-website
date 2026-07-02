FROM php:8.2-apache

# active mod rewrite (utile pour PHP sites)
RUN a2enmod rewrite

# copie ton site dans le serveur
COPY . /var/www/html/

# permissions
RUN chown -R www-data:www-data /var/www/html
