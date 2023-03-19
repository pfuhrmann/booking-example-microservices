FROM php:8.2-fpm-alpine

# Install system packages
RUN apk upgrade --update --no-cache && \
    apk add --no-cache \
        zstd-dev

# Install PHP PECL extensions
RUN apk add --no-cache --virtual buildDeps ${PHPIZE_DEPS} && \
    pecl install igbinary && docker-php-ext-enable igbinary && \
    yes | pecl install -o -f redis && docker-php-ext-enable redis && \
    apk del buildDeps &&  rm -rf /tmp/pear

# Install extra development dependencies
RUN set -xe \
    && apk update \
    && apk add --no-cache \
        autoconf \
        g++ make zlib-dev linux-headers

# Install PHP core extensions
RUN docker-php-ext-install \
        pdo_mysql \
        sockets

# Install xdebug PHP extension
RUN set -xe \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug \
    && touch /var/log/xdebug.log \
    && chown www-data:www-data /var/log/xdebug.log

# Install Composer
RUN php -r "readfile('http://getcomposer.org/installer');" | php -- --install-dir=/usr/bin/ --filename=composer

WORKDIR /app
