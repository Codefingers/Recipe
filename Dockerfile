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
RUN composer

arg MYSQLHOST
arg MYSQLDB
arg MYSQLUSER
arg MYSQLPASS

CMD ["/app/bootstrap/docker/create_env.sh ${MYSQLHOST} ${MYSQLDB} ${MYSQLUSER} ${MYSQLPASS}", "/app/bootstrap/docker/start_api.sh"]
