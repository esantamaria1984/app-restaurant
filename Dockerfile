FROM php:8.2-cli

# Instalar dependencias necesarias para Symfony y PostgreSQL
RUN apt-get update && apt-get install -y \
    git unzip curl libzip-dev zip libicu-dev libonig-dev libxml2-dev \
    default-libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql intl zip opcache

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Establecer directorio de trabajo
WORKDIR /var/www/html

# Copiar todo el código fuente al contenedor
COPY . .

# Instalar dependencias PHP del proyecto
RUN composer install --no-dev --optimize-autoloader

# Exponer el puerto que usaremos
EXPOSE 10000

# Comando para arrancar el servidor PHP built-in en la carpeta public
CMD ["php", "-S", "0.0.0.0:10000", "-t", "public"]
