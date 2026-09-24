#!/bin/sh
set -e

# Cache configuration, routes, and views if in production
if [ "$APP_ENV" = "production" ]; then
    echo "Caching Laravel config, routes, and views..."
    php artisan config:cache || true
    php artisan route:cache || true
    php artisan view:cache || true
fi

# Ensure storage link exists
php artisan storage:link || true

# Run database migrations if RUN_MIGRATIONS=true
if [ "$RUN_MIGRATIONS" = "true" ]; then
    echo "Running database migrations..."
    php artisan migrate --force || true
fi

PORT=${PORT:-8080}
echo "Configuring Nginx to listen on port ${PORT}..."
sed -i "s/listen 8080;/listen ${PORT};/g" /etc/nginx/http.d/default.conf

echo "Starting PHP-FPM..."
php-fpm -D

echo "Starting Nginx..."
exec nginx -g 'daemon off;'

