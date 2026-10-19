#!/bin/bash
mkdir -p storage/framework/{sessions,views,cache} storage/logs bootstrap/cache
chmod -R 777 storage bootstrap/cache
php artisan serve --host=0.0.0.0 --port=$PORT