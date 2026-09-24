# ==========================================
# Stage 1: Build Frontend Assets (Vite)
# ==========================================
FROM node:20-alpine AS frontend
WORKDIR /app

COPY package*.json ./
RUN npm install

COPY . .
RUN npm run build

# ==========================================
# Stage 2: PHP + Nginx Production Runtime
# ==========================================
FROM php:8.3-fpm-alpine

# Install system dependencies and Nginx
RUN apk add --no-cache \
    nginx \
    curl \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    oniguruma-dev \
    icu-dev

# Install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
        pdo_mysql \
        mbstring \
        exif \
        pcntl \
        bcmath \
        gd \
        zip \
        intl \
        opcache

# Configure PHP-FPM to pass environment variables to workers and forward logs
RUN echo "clear_env = no" >> /usr/local/etc/php-fpm.d/www.conf \
    && echo "catch_workers_output = yes" >> /usr/local/etc/php-fpm.d/www.conf \
    && echo "decorate_workers_output = no" >> /usr/local/etc/php-fpm.d/www.conf \
    && echo "php_admin_flag[log_errors] = on" >> /usr/local/etc/php-fpm.d/www.conf \
    && echo "php_admin_value[error_log] = /proc/self/fd/2" >> /usr/local/etc/php-fpm.d/www.conf


# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copy application files
COPY . /var/www/html

# Copy compiled frontend assets from Stage 1
COPY --from=frontend /app/public/build /var/www/html/public/build

# Install PHP production dependencies
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Copy Nginx config & Entrypoint script
COPY docker/nginx.conf /etc/nginx/http.d/default.conf
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

# Set directory permissions for Laravel
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

EXPOSE 8080

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
