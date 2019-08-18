#!/usr/bin/env sh

composer install -a -o --prefer-dist && \
php /var/www/bin/console doctrine:migrations:migrate --no-interaction && \
php /var/www/bin/console app:import-companies && \
php-fpm