#!/bin/bash
# Create required directories
mkdir -p storage/framework/{sessions,views,cache} storage/logs bootstrap/cache
chmod -R 777 storage bootstrap/cache

# CRITICAL: Clear ALL caches to prevent stale route conflicts
php artisan route:clear
php artisan config:clear
php artisan cache:clear

# Start the server
php artisan serve --host=0.0.0.0 --port=$PORT