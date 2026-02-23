FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    libxml2-dev \
    libzip-dev \
    libsqlite3-dev \
    unzip \
    git \
    && docker-php-ext-install soap zip pdo pdo_sqlite \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app
