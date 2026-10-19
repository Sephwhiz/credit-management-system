#!/bin/bash
mkdir -p storage/framework/{sessions,views,cache} storage/logs bootstrap/cache
chmod -R 777 storage bootstrap/cache
php artisan config:clear
php artisan route:clear
php artisan cache:clear
php artisan serve --host=0.0.0.0 --port=$PORT