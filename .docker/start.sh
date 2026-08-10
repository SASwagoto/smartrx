#!/bin/sh

# Ensure storage and cache permissions are correct
chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# Clear and Cache configuration/routes/views for production performance
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Start PHP-FPM in background
php-fpm -D

# Start Nginx in foreground
nginx -g "daemon off;"