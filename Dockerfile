FROM php:8.2-cli-bullseye

# Instalar dependencias necesarias para Symfony y PostgreSQL
RUN apt-get update && apt-get install -y \
    git unzip curl libzip-dev zip libicu-dev libonig-dev libxml2-dev libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql intl zip opcache \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Establecer el directorio de trabajo
WORKDIR /var/www/html

# Copiar el código fuente al contenedor
COPY . .

# Instalar dependencias PHP
RUN composer install --no-dev --optimize-autoloader

# Exponer el puerto que usaremos
EXPOSE 10000

# Comando para arrancar el servidor PHP integrado en la carpeta public
CMD ["php", "-S", "0.0.0.0:10000", "-t", "public"]


