FROM php:7.4-cli

USER root

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install unzip utility and libs needed by zip PHP extension
RUN apt-get update && apt-get install -y \
    zlib1g-dev \
    libzip-dev \
    unzip
RUN docker-php-ext-install zip

COPY . /app
WORKDIR /app
RUN chmod 666 bootstrap/docker/start_api.sh
RUN composer install

CMD /app/bootstrap/docker/start_api.sh
