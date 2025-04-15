FROM php:8.4-fpm

RUN apt-get update && apt-get install -y \
    curl \
    default-mysql-client \
    gettext-base \
    git \
    gnupg \
    libonig-dev \
    libpng-dev \
    libxml2-dev \
    libzip-dev \
    unzip \
    zip \
    && docker-php-ext-install bcmath exif gd mbstring pcntl pdo_mysql zip \
    && pecl install redis xdebug-3.4.2 \
    && docker-php-ext-enable redis xdebug

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN curl -fsSL https://deb.nodesource.com/setup_23.x | bash - \
    && apt-get install -y nodejs \
    && npm install -g npm@latest \
    && npm install -g pnpm \
    && npm cache clean --force \
    && pnpm store prune \
    && apt-get -y autoremove \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

RUN groupadd -g 1000 laravel && useradd -u 1000 -ms /bin/bash -g laravel laravel

WORKDIR /var/www/html

USER root

COPY ./docker/php/php.ini /usr/local/etc/php/php.ini
COPY ./docker/scripts/entrypoint.sh /usr/local/bin/entrypoint.sh
COPY ./docker/ssl/rootCA.pem /usr/local/share/ca-certificates/rootCA.crt

RUN chmod +x /usr/local/bin/entrypoint.sh
RUN update-ca-certificates

USER laravel

COPY --chown=laravel:laravel . /var/www/html
