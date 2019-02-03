FROM php:7.1-apache

LABEL maintainer="phithon <root@leavesongs.com>"

COPY www/ /var/www/html/
RUN chown root:root -R /var/www \
    && chmod 0755 -R /var/www \
    && chown www-data:www-data -R /var/www/html/data/