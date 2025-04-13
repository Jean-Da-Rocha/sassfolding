#!/usr/bin/env bash

export TRAEFIK_IP_ADDRESS=$(getent hosts "${COMPOSE_PROJECT_NAME}-traefik" | awk '{ print $1 }')

if [ -n "$TRAEFIK_IP_ADDRESS" ]; then
    sed -i "s|^TRAEFIK_IP_ADDRESS=.*|TRAEFIK_IP_ADDRESS=$TRAEFIK_IP_ADDRESS|" .env
    envsubst < docker/dnsmasq/dnsmasq.conf.template > docker/dnsmasq/dnsmasq.conf
fi

if ! grep -q "^APP_KEY=" .env || [ -z "$(grep '^APP_KEY=' .env | cut -d'=' -f2)" ]; then
    php artisan key:generate
fi

if ! grep -q "^APP_KEY=" .env.testing || [ -z "$(grep '^APP_KEY=' .env.testing | cut -d'=' -f2)" ]; then
    php artisan key:generate --env=testing
fi

if [ ! -d "vendor" ]; then
    composer install
fi

if [ ! -d "node_modules" ]; then
    pnpm install
fi

php-fpm &
pnpm run dev &

until mysqladmin ping -h"mysql" --user=root --password="${MYSQL_ROOT_PASSWORD}" --silent; do
    echo "Waiting for MySQL..."
    sleep 2
done

php artisan migrate
php artisan ide-helper:eloquent || true
php artisan ide-helper:generate || true
php artisan ide-helper:meta || true
php artisan ide-helper:models -M || true

# Keep the container alive until background processes are done.
wait -n;
