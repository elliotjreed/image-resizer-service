FROM nginx:stable-alpine

ENV TZ='Europe/London'

RUN mkdir -p /var/www/html/public && \
    { find /usr/local/lib -type f -print0 | xargs -0r strip --strip-all -p 2>/dev/null || true; } && \
    rm -rf /tmp/* /usr/local/lib/php/doc/* /var/cache/apk/*

COPY ./docker/nginx/nginx.conf /etc/nginx/nginx.conf
COPY ./public /var/www/html/public

EXPOSE 443
