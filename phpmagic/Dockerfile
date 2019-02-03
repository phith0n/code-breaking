FROM php:5.6.33-apache

LABEL maintainer="phithon <root@leavesongs.com>"

RUN set -ex \
    && sed -i 's/deb http:\/\/security\.debian\.org.*//g' /etc/apt/sources.list \
    && apt-get update \
    && apt-get install -y --no-install-recommends dnsutils \
    && rm -rf /var/lib/apt/lists/*

COPY www/ /var/www/html/
RUN chown root:root -R /var/www \
    && chmod 0755 -R /var/www \
    && chown www-data:www-data /var/www/html/data/