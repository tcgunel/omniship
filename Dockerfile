FROM php:8.2-cli

RUN apt-get update && apt-get install -y \
    libxml2-dev \
    libzip-dev \
    unzip \
    git \
    && docker-php-ext-install soap zip \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app

COPY composer.json ./
RUN composer install --no-scripts --no-interaction --prefer-dist || true

COPY . .

RUN composer install --no-interaction --prefer-dist
