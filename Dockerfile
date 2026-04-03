FROM php:8.3-apache

RUN apt-get update && apt-get install -y \
    libicu-dev \
    libpng-dev \
    libzip-dev \
    zip \
    unzip \
    && docker-php-ext-configure intl \
    && docker-php-ext-install intl gd zip mysqli pdo_mysql \
    && a2enmod rewrite


COPY --from=composer:latest /usr/bin/composer /usr/bin/composer


WORKDIR /var/www/html


RUN chown -R www-data:www-data /var/www/html