#!/bin/bash
set -e

curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install all PHP dependencies.
cd /app/
composer install

cp .env.example .env
# php artisan jwt:secret

echo "Starting web-server..."
exec php artisan serve
