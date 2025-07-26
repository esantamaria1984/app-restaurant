#!/usr/bin/env bash

# Salir si ocurre un error
set -e

# 1️⃣ Instala dependencias PHP en modo producción
composer install --no-dev --optimize-autoloader

# 2️⃣ Crea directorios de Symfony necesarios
mkdir -p var/cache var/log
chmod -R 777 var

# 3️⃣ Ejecuta las migraciones para crear las tablas en la base de datos PostgreSQL
php bin/console doctrine:migrations:migrate --no-interaction || true