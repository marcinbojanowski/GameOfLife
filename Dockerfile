FROM alpine:edge

ENV S6_OVERLAY_VERSION=v1.18.1.5

RUN apk add --update --no-cache \
    php7 php7-fpm php7-gd  \
    nginx  \
    curl && \
    curl -sSL https://github.com/just-containers/s6-overlay/releases/download/${S6_OVERLAY_VERSION}/s6-overlay-amd64.tar.gz \
    | tar xfz - -C / && \
    apk del curl && \
    rm -rf /var/cache/apk/* && \
    ln -s /usr/bin/php7 /usr/bin/php

ADD root /
ADD src /var/www

RUN chown -Rf nginx:nginx /var/www


EXPOSE 80
VOLUME /var/www


ENTRYPOINT ["/init"]