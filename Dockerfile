FROM php:8.2-cli

#Instalar dependencias

RUN apt-get update && apt-get install -y \
    git unzip curl libzip-dev zip libicu-dev libonig-dev libxml2-dev \
    && docker-php-ext-install pdo pdo_mysql intl zip opcache

#Instalar composer

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

#Establecer directorio de trabajo

WORKDIR /var/www/html