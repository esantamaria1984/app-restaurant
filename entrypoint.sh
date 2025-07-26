#!/bin/sh

# Ejecutar migraciones de Doctrine
echo "Ejecutando migraciones..."
php bin/console doctrine:migrations:migrate --no-interaction

# Arrancar servidor PHP integrado apuntando a la carpeta public
echo "Iniciando servidor PHP..."
php -S 0.0.0.0:10000 -t public