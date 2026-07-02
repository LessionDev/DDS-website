FROM php:8.2-cli

WORKDIR /app

COPY . /app

# installe les extensions MySQL correctement
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libonig-dev \
    libzip-dev \
    unzip \
    && docker-php-ext-install mysqli pdo pdo_mysql

# IMPORTANT: utiliser le port Railway
CMD ["sh", "-c", "php -S 0.0.0.0:$PORT -t /app"]
