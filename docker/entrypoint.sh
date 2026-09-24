#!/bin/sh
set -e

# Default settings
export LOG_CHANNEL=${LOG_CHANNEL:-stderr}
export SESSION_DRIVER=${SESSION_DRIVER:-file}
export CACHE_STORE=${CACHE_STORE:-file}

# Fallback APP_KEY if not provided
if [ -z "$APP_KEY" ]; then
    export APP_KEY="base64:lvSo0CYqLjM/k7J8YD2t1HA7P06DHbeFoiTmDdrKB+4="
fi

# Ensure all storage and cache directories exist with full permissions
mkdir -p /var/www/html/storage/framework/sessions \
         /var/www/html/storage/framework/views \
         /var/www/html/storage/framework/cache/data \
         /var/www/html/storage/logs \
         /var/www/html/bootstrap/cache \
         /var/www/html/database

# Ensure SQLite file exists if using SQLite
if [ "$DB_CONNECTION" = "sqlite" ] || [ -z "$DB_CONNECTION" ]; then
    touch /var/www/html/database/database.sqlite
fi

chmod -R 777 /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/database
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/database

# Link storage
php artisan storage:link || true

# Run database migrations safely
echo "Checking database and running migrations..."
php artisan migrate --force || true

# Clear all previous caches
php artisan optimize:clear || true

# Cache configs
if [ "$APP_ENV" = "production" ]; then
    echo "Caching Laravel configuration..."
    php artisan config:cache || true
    php artisan route:cache || true
    php artisan view:cache || true
fi

# Configure Nginx port dynamically (Render default is 10000, Koyeb is 8080)
PORT=${PORT:-8080}
echo "Configuring Nginx to listen on port ${PORT}..."
sed -i "s/listen 8080;/listen ${PORT};/g" /etc/nginx/http.d/default.conf

echo "Starting PHP-FPM..."
php-fpm -D

echo "Starting Nginx..."
exec nginx -g 'daemon off;'
