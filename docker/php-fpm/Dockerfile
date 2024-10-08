FROM php:8.3.0-fpm-alpine

# Install packages
RUN apk add --no-cache curl git build-base zlib-dev oniguruma-dev autoconf bash linux-headers postgresql-dev

RUN pecl install xdebug-3.3.2 && docker-php-ext-enable xdebug
COPY ./docker/php-fpm/xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini


# Postgres
RUN docker-php-ext-install pdo_pgsql


# Configure non-root user.
ARG PUID=1000
ARG PGID=1000
RUN apk --no-cache add shadow && \
    groupmod -o -g ${PGID} www-data && \
    usermod -o -u ${PUID} -g www-data www-data

USER www-data


# Source code
COPY ./ /var/www
WORKDIR /var/www

# Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
#RUN composer install --no-interaction

CMD php-fpm

EXPOSE 9000
