#!/bin/sh
set -e

if [ ! -f .env ]; then
    cp .env.example .env
fi

if [ ! -d vendor ]; then
    composer install --no-interaction --prefer-dist
fi

if [ -z "$(php artisan key:show --no-ansi 2>/dev/null | grep base64 || true)" ]; then
    php artisan key:generate --force
fi

echo "Attente de PostgreSQL..."
until php -r "new PDO('pgsql:host=' . getenv('DB_HOST') . ';port=' . getenv('DB_PORT') . ';dbname=' . getenv('DB_DATABASE'), getenv('DB_USERNAME'), getenv('DB_PASSWORD'));" 2>/dev/null; do
    sleep 2
done

php artisan migrate --force

php artisan serve --host=0.0.0.0 --port=8000
