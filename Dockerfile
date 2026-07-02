FROM php:8.2-cli

WORKDIR /app

COPY . /app

# installe l’extension mysqli + pdo mysql
RUN docker-php-ext-install mysqli pdo pdo_mysql

CMD php -S 0.0.0.0:$PORT
