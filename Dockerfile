FROM php:8.2-cli-bullseye

# Forzar uso de HTTPS en repositorios y actualizar dependencias
RUN apt-get update && apt-get install -y apt-transport-https ca-certificates \
    && sed -i 's|http://deb.debian.org|https://deb.debian.org|g' /etc/apt/sources.list \
    && apt-get update && apt-get install -y --no-install-recommends \
        git unzip curl libzip-dev zip libicu-dev libonig-dev libxml2-dev default-libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql intl zip opcache \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY . .

RUN composer install --no-dev --optimize-autoloader

EXPOSE 10000

CMD ["php", "-S", "0.0.0.0:10000", "-t", "public"]


