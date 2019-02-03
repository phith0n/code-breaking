FROM php:7.2-apache

LABEL maintainer="phithon <root@leavesongs.com>"

RUN set -ex \
    && apt-get update && apt-get install -y \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libpng-dev \
        libzip-dev \
    && docker-php-ext-install -j$(nproc) iconv zip pdo_mysql mysqli \
    && docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ \
    && docker-php-ext-install -j$(nproc) gd 

RUN set -ex \
    && cd / \
    && mkdir -p /var/lib/php/sessions \
    && chown www-data:www-data -R /var/lib/php/sessions \
    && rm -rf /var/www/* \
    && a2enmod rewrite

COPY cat/ /var/www/

RUN set -ex \
    && cd / \
    && curl -s https://getcomposer.org/installer \
        | php -- --install-dir=/usr/local/bin --filename=composer \
    && chmod +x /usr/local/bin/composer \
    && cd /var/www \
    && composer update

RUN set -ex \
    && cd /var/www \
    && mkdir html/upload/ \
    && chown root:root -R . \
    && chmod 0755 -R . \
    && chown www-data:www-data -R storage/app/ storage/framework/cache/ storage/framework/views/ storage/logs/ html/upload/
