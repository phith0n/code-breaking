FROM php:5.6-fpm

LABEL maintainer="phithon <root@leavesongs.com>"

COPY ./www/ /var/www/html/

RUN set -ex \
    && chown root:root -R /var/www/ \
    && chmod 0755 -R /var/www