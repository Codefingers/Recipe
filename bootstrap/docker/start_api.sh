#!/bin/bash
set -e

php artisan key:generate --no-interaction
php artisan jwt:secret --no-interaction
php artisan migrate

echo "Starting web-server..."
exec php artisan serve --host=0.0.0.0 --port=80
