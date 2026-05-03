#!/bin/bash
set -e

# Generar clave si no existe
php artisan key:generate --force

# Cachear configuración para producción
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Ejecutar migraciones
php artisan migrate --force

# Arrancar Apache
apache2-foreground