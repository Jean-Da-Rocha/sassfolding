#!/usr/bin/env bash

if ! grep -q "^APP_KEY=" /var/www/html/.env || [ -z "$(grep '^APP_KEY=' /var/www/html/.env | cut -d'=' -f2)" ]; then
    php /var/www/html/artisan key:generate
fi

if ! grep -q "^APP_KEY=" /var/www/html/.env.testing || [ -z "$(grep '^APP_KEY=' /var/www/html/.env.testing | cut -d'=' -f2)" ]; then
    php /var/www/html/artisan key:generate --env=testing
fi

php /var/www/html/artisan config:clear
php /var/www/html/artisan route:clear

if [ ! -d "/var/www/html/vendor" ]; then
    composer install
fi

if [ ! -d "/var/www/html/node_modules" ]; then
    pnpm install
fi

php /var/www/html/artisan config:cache
php /var/www/html/artisan route:cache

until mysqladmin ping -h"mysql" --user=root --password="${MYSQL_ROOT_PASSWORD}" --silent; do
    echo "Waiting for MySQL..."
    sleep 2
done

php /var/www/html/artisan migrate
php /var/www/html/artisan ide-helper:eloquent || true
php /var/www/html/artisan ide-helper:generate || true
php /var/www/html/artisan ide-helper:meta || true
php /var/www/html/artisan ide-helper:models -M || true

php-fpm &
pnpm run dev &

wait -n
