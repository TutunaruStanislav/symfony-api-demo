FROM nginx:latest

COPY nginx.conf /etc/nginx/
COPY default.conf /etc/nginx/conf.d/

RUN apt update && apt upgrade -y && apt install -y bash

RUN addgroup -gid 82 -shell www-data ; \
    adduser -u 82 -disabled-password -shell -ingroup www-data www-data

# Set upstream conf and remove the default conf
ARG PHP_UPSTREAM_CONTAINER=php-fpm
ARG PHP_UPSTREAM_PORT=9000
RUN echo "upstream php-upstream { server ${PHP_UPSTREAM_CONTAINER}:${PHP_UPSTREAM_PORT}; }" > /etc/nginx/conf.d/upstream.conf

EXPOSE 80
