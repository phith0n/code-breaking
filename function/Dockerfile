FROM php:7.2-apache

LABEL maintainer="phithon <root@leavesongs.com>"

COPY ./www/ /var/www/html/

RUN set -ex \
    && chown root:root -R /var/www \
    && chmod 0755 -R /var/www \