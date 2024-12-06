FROM php:8.1-fpm-alpine

WORKDIR /var/www/app

COPY .. /var/www/app

RUN apk update && \
    apk add libpq-dev && \
    docker-php-ext-install pdo pdo_pgsql pgsql

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

