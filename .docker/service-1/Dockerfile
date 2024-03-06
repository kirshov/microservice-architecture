ARG PHP_BASEIMAGE_VERSION=8.2
FROM php:${PHP_BASEIMAGE_VERSION}-fpm-alpine

RUN wget https://getcomposer.org/installer -O - -q | php -- --install-dir=/bin --filename=composer --quiet

ENV COMPOSER_ALLOW_SUPERUSER 1

COPY ./app /app
WORKDIR /app
