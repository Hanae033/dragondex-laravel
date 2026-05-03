#!/bin/bash
set -e

# Escribir el .env completo directamente con las variables de Render
# APP_KEY ya viene como variable de entorno de Render → no necesitamos key:generate
cat > /var/www/html/.env << EOF
APP_NAME=DragonDex
APP_ENV=${APP_ENV:-production}
APP_KEY=${APP_KEY}
APP_DEBUG=${APP_DEBUG:-false}
APP_URL=${APP_URL:-http://localhost}

LOG_CHANNEL=${LOG_CHANNEL:-stderr}
LOG_LEVEL=error

DB_CONNECTION=${DB_CONNECTION:-pgsql}
DATABASE_URL=${DATABASE_URL}

CACHE_STORE=file
SESSION_DRIVER=file
QUEUE_CONNECTION=sync
EOF

echo "✅ .env creado correctamente"

# Limpiar cachés viejas
php artisan config:clear
php artisan cache:clear

# Cachear para producción
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "✅ Cachés generadas"

# Ejecutar migraciones
php artisan migrate --force

echo "✅ Migraciones ejecutadas"

# Arrancar Apache
apache2-foreground