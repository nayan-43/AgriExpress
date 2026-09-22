# ============================================
# Stage 1: Build Tailwind/Vite assets
# ============================================
FROM node:24-alpine AS frontend

WORKDIR /app

COPY package*.json ./

RUN npm ci

COPY . .

RUN npm run build


# ============================================
# Stage 2: Laravel application
# ============================================
FROM php:8.3-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    nginx \
    git \
    unzip \
    libpq-dev \
    libzip-dev \
    libicu-dev \
    && docker-php-ext-install \
        pdo_pgsql \
        bcmath \
        zip \
        intl \
    && rm -rf /var/lib/apt/lists/*


# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer


WORKDIR /var/www/html


# Copy Laravel project
COPY . .


# Install PHP dependencies
RUN composer install \
    --no-dev \
    --no-interaction \
    --prefer-dist \
    --optimize-autoloader


# Copy compiled Vite/Tailwind assets
COPY --from=frontend /app/public/build ./public/build


# Laravel writable directories
RUN chown -R www-data:www-data \
    storage \
    bootstrap/cache


# Nginx configuration
COPY docker/nginx.conf /etc/nginx/sites-available/default


# Startup script
COPY docker/start.sh /start.sh

RUN chmod +x /start.sh


EXPOSE 10000


CMD ["/start.sh"]