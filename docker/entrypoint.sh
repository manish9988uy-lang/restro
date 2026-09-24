#!/bin/sh
set -e

# Default LOG_CHANNEL to stderr if not set so logs appear in platform console
export LOG_CHANNEL=${LOG_CHANNEL:-stderr}

# Ensure database directory and SQLite file exist if using SQLite
if [ "$DB_CONNECTION" = "sqlite" ] || [ -z "$DB_CONNECTION" ]; then
    mkdir -p /var/www/html/database
    touch /var/www/html/database/database.sqlite
    chown -R www-data:www-data /var/www/html/database
fi

# Ensure storage and cache directories exist with correct permissions
mkdir -p /var/www/html/storage/framework/sessions \
         /var/www/html/storage/framework/views \
         /var/www/html/storage/framework/cache \
         /var/www/html/storage/logs \
         /var/www/html/bootstrap/cache

chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Generate APP_KEY if not already set
if [ -z "$APP_KEY" ]; then
    echo "APP_KEY is empty. Generating key..."
    php artisan key:generate --force || true
fi

# Link storage
php artisan storage:link || true

# Run database migrations
echo "Running database migrations..."
php artisan migrate --force || true

# Clear and rebuild cache
php artisan optimize:clear || true
if [ "$APP_ENV" = "production" ]; then
    echo "Caching Laravel configuration..."
    php artisan config:cache || true
    php artisan route:cache || true
    php artisan view:cache || true
fi

# Configure Nginx port dynamically
PORT=${PORT:-8080}
echo "Configuring Nginx to listen on port ${PORT}..."
sed -i "s/listen 8080;/listen ${PORT};/g" /etc/nginx/http.d/default.conf

echo "Starting PHP-FPM..."
php-fpm -D

echo "Starting Nginx..."
exec nginx -g 'daemon off;'
