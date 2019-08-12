FROM php:7.2-fpm-alpine

ARG USER_ID

RUN apk add --no-cache autoconf g++ make shadow;

RUN docker-php-source extract \
    && pecl install -o -f xdebug \
    && rm -rf /tmp/pear \
    && docker-php-ext-install pdo_mysql \
    && docker-php-ext-enable xdebug \
    && docker-php-source delete

COPY php/config/xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini

COPY entrypoint.sh /app-scripts/entrypoint.sh

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" && \
    php composer-setup.php --filename=composer --install-dir=/usr/local/bin/ && \
    php -r "unlink('composer-setup.php');"

RUN usermod -u $USER_ID www-data
RUN rm -R /var/www/html

WORKDIR /var/www

USER www-data

ENTRYPOINT [ "/app-scripts/entrypoint.sh" ]