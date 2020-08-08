#!/bin/bash
set -e

cp .env.example .env
php artisan key:generate --no-interaction
php artisan jwt:secret --no-interaction

echo "Starting web-server..."
exec php artisan serve --host=0.0.0.0 --port=80
