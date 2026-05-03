#!/bin/bash
set -e

echo "🔧 Configurando .env..."

# Usar Python para parsear DATABASE_URL de forma fiable
# Python maneja URLs con o sin puerto, con caracteres especiales, etc.
eval $(python3 -c "
import urllib.parse, os

url = os.environ.get('DATABASE_URL', '')
if not url:
    print('echo ERROR: DATABASE_URL no está definida')
    exit(1)

p = urllib.parse.urlparse(url)
print(f'DB_HOST={p.hostname}')
print(f'DB_PORT={p.port if p.port else 5432}')
print(f'DB_DATABASE={p.path.lstrip(\"/\")}')
print(f'DB_USERNAME={p.username}')
print(f'DB_PASSWORD={p.password}')
")

echo "📍 Conectando a: ${DB_HOST}:${DB_PORT} / ${DB_DATABASE}"

# Escribir el .env completo con los datos ya parseados
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
DB_PORT=${DB_PORT}
DB_DATABASE=${DB_DATABASE}
DB_USERNAME=${DB_USERNAME}
DB_PASSWORD=${DB_PASSWORD}

CACHE_STORE=file
SESSION_DRIVER=file
QUEUE_CONNECTION=sync
EOF

echo "✅ .env creado"

php artisan config:clear
php artisan cache:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "✅ Cachés generadas"

php artisan migrate --force

echo "✅ Migraciones ejecutadas"

apache2-foreground