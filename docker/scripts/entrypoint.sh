#!/usr/bin/env bash

if [ ! -f .env ]; then
    cp .env.example .env
    php artisan key:generate
fi

if [ ! -d "vendor" ]; then
    composer install
fi

if [ ! -d "node_modules" ]; then
    pnpm install
fi

php artisan migrate

php artisan ide-helper:eloquent || true
php artisan ide-helper:generate || true
php artisan ide-helper:meta || true
php artisan ide-helper:models -M || true

php-fpm & pnpm run dev
