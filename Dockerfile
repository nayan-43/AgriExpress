
# ============================================
# Stage 1: Build frontend assets
# ============================================
FROM node:24-alpine AS frontend

WORKDIR /app

# Install frontend dependencies
COPY package*.json ./

RUN npm ci

# Copy Laravel project files
COPY . .

# Build Vite / Tailwind assets
RUN npm run build


# ============================================
# Stage 2: Laravel application
# ============================================
FROM php:8.3-fpm

WORKDIR /var/www/html

# ============================================
# Install system dependencies and PHP extensions
# ============================================
RUN apt-get update && apt-get install -y \
    nginx git unzip \
    libpq-dev libzip-dev libicu-dev \
    libfreetype-dev libjpeg62-turbo-dev libpng-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j"$(nproc)" \
        pdo_pgsql \
        bcmath \
        zip \
        intl \
        opcache \
        gd \
    && rm -rf /var/lib/apt/lists/*

# ============================================
# Configure PHP OPcache
# ============================================
RUN { \
    echo "opcache.enable=1"; \
    echo "opcache.enable_cli=0"; \
    echo "opcache.memory_consumption=128"; \
    echo "opcache.interned_strings_buffer=16"; \
    echo "opcache.max_accelerated_files=20000"; \
    echo "opcache.validate_timestamps=0"; \
    echo "opcache.save_comments=1"; \
} > /usr/local/etc/php/conf.d/opcache.ini


# ============================================
# Install Composer
# ============================================
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer


# ============================================
# Copy Laravel application
# ============================================
COPY . .


# ============================================
# Install production dependencies
# ============================================
RUN composer install \
    --no-dev \
    --no-interaction \
    --prefer-dist \
    --optimize-autoloader \
    --no-progress


# ============================================
# Copy compiled frontend assets
# ============================================
COPY --from=frontend /app/public/build ./public/build


# ============================================
# Laravel permissions
# ============================================
RUN mkdir -p \
    storage/framework/cache \
    storage/framework/sessions \
    storage/framework/views \
    storage/logs \
    bootstrap/cache \
    && chown -R www-data:www-data \
        storage \
        bootstrap/cache


# ============================================
# Nginx configuration
# ============================================
COPY docker/nginx.conf /etc/nginx/sites-available/default


# ============================================
# Startup script
# ============================================
COPY docker/start.sh /start.sh

RUN chmod +x /start.sh


# ============================================
# Expose Render port
# ============================================
EXPOSE 10000


# ============================================
# Start application
# ============================================
CMD ["/start.sh"]
