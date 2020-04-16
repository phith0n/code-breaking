FROM httpd:2.4.43

LABEL maintainer="phith0n <root@leavesongs.com>"

RUN set -ex \
    && apt-get update \
    && apt-get install -y --no-install-recommends curl ca-certificates

ADD src/ /usr/local/apache2/htdocs/
ADD serve-cgi-bin.conf /usr/local/apache2/conf/extra/
RUN set -ex \
    && sed -i 's|#LoadModule cgid_module modules/mod_cgid.so|LoadModule cgid_module modules/mod_cgid.so|' /usr/local/apache2/conf/httpd.conf \
    && echo "Include conf/extra/serve-cgi-bin.conf" >> /usr/local/apache2/conf/httpd.conf \
    && chown -R daemon:daemon /usr/local/apache2/htdocs \
    && chmod +x /usr/local/apache2/htdocs/index.cgi