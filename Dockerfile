FROM php:8.2-cli

# Cambiar mirror para evitar problemas con apt
RUN sed -i 's/deb.debian.org/ftp.debian.org/g' /etc/apt/sources.list

# Instalar dependencias necesarias
RUN apt-get update && apt-get install -y --no-install-recommends \
    git unzip curl libzip-dev zip libicu-dev libonig-dev libxml2-dev default-libpq-dev

RUN docker-php-ext-install pdo pdo_pgsql intl zip opcache

RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY . .

RUN composer install --no-dev --optimize-autoloader

EXPOSE 10000

CMD ["php", "-S", "0.0.0.0:10000", "-t", "public"]
