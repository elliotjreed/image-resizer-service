FROM elliotjreed/symfony-php-fpm-docker:php8.1

LABEL Description="Image Server PHP Docker image." Vendor="Elliot J. Reed" Version="1.0"

ENV TZ='Europe/London'

WORKDIR /var/www/html

COPY ./docker/php/php-ini-overrides.ini /usr/local/etc/php/conf.d/99-overrides.ini
COPY ./docker/php/fpm.conf /usr/local/etc/php-fpm.conf

USER root
RUN apk add --no-cache imagemagick imagemagick-dev && \
    apk add --no-cache --virtual .build-deps $PHPIZE_DEPS && \
    pecl install -o -f imagick && \
    docker-php-ext-enable imagick && \
    apk del .build-deps

RUN chown -R www-data:www-data /var/www/html && \
    touch /usr/local/var/log/php-fpm.log && \
    chmod 777 /usr/local/var/log/php-fpm.log

USER www-data

RUN mkdir -p /var/www/html/var/cache/prod && \
    mkdir -p /var/www/html/var/log && \
    chmod -R 777 /var/www/html/var

COPY --chown=www-data:www-data ./composer.json /var/www/html/composer.json
COPY --chown=www-data:www-data ./config /var/www/html/config
COPY --chown=www-data:www-data ./public /var/www/html/public
COPY --chown=www-data:www-data ./src /var/www/html/src
COPY --chown=www-data:www-data ./templates /var/www/html/templates
COPY --chown=www-data:www-data ./vendor /var/www/html/vendor
COPY --chown=www-data:www-data ./bin/console /var/www/html/bin/console

RUN chmod +x /var/www/html/bin/console
