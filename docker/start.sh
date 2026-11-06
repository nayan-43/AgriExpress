#!/bin/sh

set -e

echo "Starting Laravel application..."

cd /var/www/html

# Build production caches
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "Starting PHP-FPM..."

php-fpm -D

echo "Starting Nginx..."

exec nginx -g "daemon off;"