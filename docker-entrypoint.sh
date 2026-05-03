#!/bin/bash
set -e

echo "🔧 Configurando .env..."

# Parsear DATABASE_URL para extraer los datos de conexión
# Formato: postgres://usuario:contraseña@host:puerto/basededatos
if [ -n "$DATABASE_URL" ]; then
    # Extraer cada parte de la URL
    DB_USERNAME=$(echo $DATABASE_URL | sed 's/postgres:\/\/\([^:]*\):.*/\1/')
    DB_PASSWORD=$(echo $DATABASE_URL | sed 's/postgres:\/\/[^:]*:\([^@]*\)@.*/\1/')
    DB_HOST=$(echo $DATABASE_URL | sed 's/postgres:\/\/[^@]*@\([^:]*\):.*/\1/')
    DB_PORT=$(echo $DATABASE_URL | sed 's/postgres:\/\/[^@]*@[^:]*:\([^\/]*\)\/.*/\1/')
    DB_DATABASE=$(echo $DATABASE_URL | sed 's/postgres:\/\/[^@]*@[^\/]*\/\(.*\)/\1/')
fi

# Escribir el .env completo con los datos extraídos
cat > /var/www/html/.env << EOF
APP_NAME=DragonDex
APP_ENV=${APP_ENV:-production}
APP_KEY=${APP_KEY}
APP_DEBUG=${APP_DEBUG:-false}
APP_URL=${APP_URL:-http://localhost}

LOG_CHANNEL=${LOG_CHANNEL:-stderr}
LOG_LEVEL=error

DB_CONNECTION=pgsql
DB_HOST=${DB_HOST}
DB_PORT=${DB_PORT:-5432}
DB_DATABASE=${DB_DATABASE}
DB_USERNAME=${DB_USERNAME}
DB_PASSWORD=${DB_PASSWORD}

CACHE_STORE=file
SESSION_DRIVER=file
QUEUE_CONNECTION=sync
EOF

echo "✅ .env creado con host: ${DB_HOST}, BD: ${DB_DATABASE}"

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