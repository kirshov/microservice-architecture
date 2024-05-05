ARG PHP_BASEIMAGE_VERSION=8.2
FROM php:${PHP_BASEIMAGE_VERSION}-fpm

RUN apt-get update && apt-get install -y \
    libpq-dev \
    wget \
    zlib1g-dev \
    libmcrypt-dev \
    libzip-dev \
    nano \
    zip

RUN docker-php-ext-install pdo pdo_pgsql

RUN pecl install apcu \
   && docker-php-ext-enable apcu

RUN wget https://getcomposer.org/installer -O - -q | php -- --install-dir=/bin --filename=composer --quiet

ENV COMPOSER_ALLOW_SUPERUSER 1

COPY ./app /app
COPY .docker/users-service/custom-php.ini /usr/local/etc/php/conf.d/custom-php.ini

WORKDIR /app
