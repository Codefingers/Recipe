FROM php:7.4-cli

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install unzip utility and libs needed by zip PHP extension
RUN apt-get update && apt-get install -y \
    zlib1g-dev \
    libzip-dev \
    unzip
RUN docker-php-ext-install zip

COPY . /app
RUN cd /app/bootstrap/docker && chmod 666 start_api.sh
RUN cd /app && composer install

CMD ./app/bootstrap/docker/start_api.sh
