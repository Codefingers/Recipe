FROM php:7.4-cli

USER root

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install unzip utility and libs needed by zip PHP extension
RUN apt-get update && apt-get install -y \
    zlib1g-dev \
    libzip-dev \
    unzip
RUN docker-php-ext-install zip mysqli pdo pdo_mysql

COPY . /app
WORKDIR /app
RUN composer install

arg MYSQLHOST
arg MYSQLDB
arg MYSQLUSER
arg MYSQLPASS

env MYSQLHOST=$MYSQLHOST
env MYSQLDB=$MYSQLDB
env MYSQLUSER=$MYSQLUSER
env MYSQLPASS=$MYSQLPASS

CMD /app/bootstrap/docker/create_env.sh $MYSQLHOST $MYSQLDB $MYSQLUSER $MYSQLPASS; /app/bootstrap/docker/start_api.sh;
