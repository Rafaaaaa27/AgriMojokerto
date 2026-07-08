#!/bin/bash
set -e

php -r "copy('https://getcomposer.org/installer', '/tmp/composer-setup.php');"
php /tmp/composer-setup.php --install-dir=/tmp --filename=composer --quiet
/tmp/composer install --no-dev --optimize-autoloader
cp .env.example .env
php artisan key:generate
