FROM php:8.2-cli

WORKDIR /app
COPY . /app

--env-file

RUN docker-php-ext-install mysqli pdo pdo_mysql

CMD ["php", "-S", "0.0.0.0:8080", "-t", "/app"]
