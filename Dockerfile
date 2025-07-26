FROM php:8.2-cli-bullseye

# Instalar dependencias necesarias para Symfony y PostgreSQL
RUN apt-get update && apt-get install -y \
    git unzip curl libzip-dev zip libicu-dev libonig-dev libxml2-dev libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql intl zip opcache \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Instalar Composer desde la imagen oficial
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Establecer el directorio de trabajo
WORKDIR /var/www/html

# Copiar el código fuente al contenedor
COPY . .

# Instalar las dependencias PHP del proyecto Symfony sin ejecutar scripts (para evitar fallos)
RUN composer install --no-dev --optimize-autoloader --no-scripts

# Ejecutar migraciones automáticamente (sin interacción)
RUN php bin/console doctrine:migrations:migrate --no-interaction

# Exponer el puerto que usaremos
EXPOSE 10000

# Comando para iniciar el servidor integrado de PHP apuntando a la carpeta "public"
CMD ["php", "-S", "0.0.0.0:10000", "-t", "public"]



