FROM php:8.2-apache

WORKDIR /var/www/html

COPY . /var/www/html

# active mysqli + pdo mysql
RUN docker-php-ext-install mysqli pdo pdo_mysql

# active rewrite (optionnel mais utile)
RUN a2enmod rewrite

EXPOSE 80
