FROM php:8.2-fpm-buster

# Install packages
RUN apt update \
    && apt upgrade -y \
    && apt install -y \
    curl git autoconf bash unzip

# Install Xdebug
RUN pecl install xdebug && docker-php-ext-enable xdebug
COPY ./.docker/php-fpm/xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini

# Install extensions
RUN apt update && apt install -y zlib1g-dev libpng-dev libxml2-dev libzip-dev libpq-dev && rm -rf /var/lib/apt/lists/*
RUN docker-php-ext-install \
    gd \
    soap \
    opcache \
    sockets \
    zip \
    pdo_pgsql

# Configure non-root user
ARG PUID=1000
ARG PGID=1000
RUN groupmod -o -g ${PGID} www-data && \
    usermod -o -u ${PUID} -g www-data www-data

# Source code
RUN chown www-data:www-data /var/www
COPY --chown=www-data:www-data ./ /var/www
WORKDIR /var/www

USER www-data

## Composer
#COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
#RUN composer install --no-interaction

CMD php-fpm

EXPOSE 9000
