FROM php:8.2-fpm

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN apt-get update \
 && apt-get install -y git unzip libzip-dev \
 && docker-php-ext-install pdo_mysql zip

WORKDIR /var/www