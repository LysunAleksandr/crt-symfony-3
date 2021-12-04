FROM composer:latest as composer
FROM php:8.0-fpm-alpine as php

RUN set -xe && apk update && apk upgrade

RUN set -xe \
    && apk add --no-cache \
       shadow \
       libzip-dev \
       libintl \
       icu \
       icu-dev \
       bash \
       curl \
       libmcrypt \
       libmcrypt-dev \
       libxml2-dev \
       freetype \
       freetype-dev \
       libpng \
       libpng-dev \
       libjpeg-turbo \
       libjpeg-turbo-dev \
       postgresql-dev \
       mariadb-dev \
       pcre-dev \
       git \
       g++ \
       make \
       autoconf \
       openssh \
       util-linux-dev \
       libuuid \
       sqlite-dev

RUN docker-php-ext-install -j$(nproc) \
        zip \
        gd \
        intl \
        soap \
        sockets \
        opcache \
        pcntl \
        sockets \
        exif \
        pdo_pgsql

RUN pecl update-channels && \
    pecl install uuid && \
    docker-php-ext-enable uuid && \
    pecl install pcov && \
    docker-php-ext-enable pcov

RUN apk add gnu-libiconv --no-cache --repository http://dl-cdn.alpinelinux.org/alpine/edge/community/ --allow-untrusted
ENV LD_PRELOAD /usr/lib/preloadable_libiconv.so php

COPY --from=composer /usr/bin/composer /usr/local/bin/composer
RUN composer -V

WORKDIR /var/www/app
ENV COMPOSER_ALLOW_SUPERUSER=1
ENV COMPOSER_MEMORY_LIMIT=-1

ARG PUID=1000
ENV PUID ${PUID}
ARG PGID=1000
ENV PGID ${PGID}

RUN usermod -u ${PUID} www-data && groupmod -g ${PGID} www-data
RUN mkdir -p /var/www/app && chown -R www-data:www-data /var/www/app

RUN echo 'memory_limit=1536M' > /usr/local/etc/php/conf.d/memory.ini
RUN echo 'upload_max_filesize = 10M' > /usr/local/etc/php/conf.d/upload-size.ini
RUN echo 'post_max_size = 10M' >> /usr/local/etc/php/conf.d/upload-size.ini

USER ${PUID}:${PGID}
