FROM node:11-alpine

LABEL maintainer="phithon <root@leavesongs.com>"

COPY www/ /usr/src

RUN set -ex \
    && apk add --no-cache --virtual .gyp \
        python \
        make \
        g++ \
    && cd /usr/src \
    && rm yarn.lock \
    && yarn install --production --network-timeout 30000 \
    && apk del .gyp

WORKDIR /usr/src
CMD ["node", "index.js"]
