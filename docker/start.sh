#!/bin/sh

set -e

echo "Starting Laravel application..."

# Clear cached configuration
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Build production caches
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "Starting PHP-FPM..."

php-fpm -D

echo "Starting Nginx..."

nginx -g "daemon off;"