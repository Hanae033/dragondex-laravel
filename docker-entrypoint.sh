#!/bin/bash
set -e

echo "🔧 Configurando entorno..."

# Parsear DATABASE_URL con PHP (siempre disponible en el contenedor)
if [ -n "$DATABASE_URL" ]; then
    DB_PARSED=$(php -r "
        \$url = parse_url(getenv('DATABASE_URL'));
        echo 'DB_HOST=' . \$url['host'] . PHP_EOL;
        echo 'DB_PORT=' . (\$url['port'] ?? 5432) . PHP_EOL;
        echo 'DB_DATABASE=' . ltrim(\$url['path'], '/') . PHP_EOL;
        echo 'DB_USERNAME=' . \$url['user'] . PHP_EOL;
        echo 'DB_PASSWORD=' . \$url['pass'] . PHP_EOL;
    ")
    eval "$DB_PARSED"
fi

echo "📍 BD: ${DB_HOST}:${DB_PORT}/${DB_DATABASE}"

# Crear .env completo
cat > /var/www/html/.env << EOF
APP_NAME=DragonDex
APP_ENV=production
APP_KEY=${APP_KEY}
APP_DEBUG=false
APP_URL=${APP_URL:-http://localhost}

LOG_CHANNEL=stderr
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