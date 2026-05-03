#!/bin/bash
set -e

echo "🔧 Configurando .env..."

# Validar DATABASE_URL
if [ -z "$DATABASE_URL" ]; then
  echo "❌ ERROR: DATABASE_URL no está definida"
  exit 1
fi

# Parseo seguro con bash (sin python)
DB_HOST=$(echo $DATABASE_URL | sed -E 's|.*@([^:/]+):.*|\1|')
DB_PORT=$(echo $DATABASE_URL | sed -E 's|.*:([0-9]+)/.*|\1|')
DB_DATABASE=$(echo $DATABASE_URL | sed -E 's|.*/([^?]+).*|\1|')
DB_USERNAME=$(echo $DATABASE_URL | sed -E 's|.*//([^:]+):.*@\w+.*|\1|')
DB_PASSWORD=$(echo $DATABASE_URL | sed -E 's|.*//[^:]+:([^@]+)@.*|\1|')

echo "📍 Conectando a: $DB_HOST:$DB_PORT / $DB_DATABASE"

# Crear .env limpio
cat > /var/www/html/.env << EOF
APP_NAME=DragonDex
APP_ENV=production
APP_KEY=${APP_KEY}
APP_DEBUG=false
APP_URL=${APP_URL}

LOG_CHANNEL=stderr
LOG_LEVEL=error

DB_CONNECTION=pgsql
DB_HOST=${DB_HOST}
DB_PORT=${DB_PORT}
DB_DATABASE=${DB_DATABASE}
DB_USERNAME=${DB_USERNAME}
DB_PASSWORD=${DB_PASSWORD}

CACHE_STORE=file
SESSION_DRIVER=file
QUEUE_CONNECTION=sync
EOF

echo "✅ .env creado"

# Laravel cache
php artisan config:clear
php artisan cache:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "✅ Cachés generadas"

# Migraciones
php artisan migrate --force

echo "✅ Migraciones ejecutadas"

# Iniciar Apache
apache2-foreground