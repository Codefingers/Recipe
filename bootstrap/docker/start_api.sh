#!/bin/bash
set -e

curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install all PHP dependencies.
cd /app/

# Install unzip utility and libs needed by zip PHP extension
apt-get update && apt-get install -y \
    zlib1g-dev \
    libzip-dev \
    unzip
docker-php-ext-install zip

composer install

cp .env.example .env
php artisan key:generate --no-interaction
php artisan jwt:secret --no-interaction

echo "Starting web-server..."
exec php artisan serve --host=0.0.0.0 --port=80
