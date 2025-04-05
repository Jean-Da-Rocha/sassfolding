FROM php:8.4-fpm

RUN apt-get update && apt-get install -y \
    unzip \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    zip \
    default-mysql-client \
    gettext-base \
    git && \
    docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

RUN pecl install redis && docker-php-ext-enable redis

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN curl -fsSL https://deb.nodesource.com/setup_23.x | bash - && \
    apt-get install -y nodejs && \
    npm install -g npm@latest && \
    npm install -g pnpm

RUN groupadd -g 1000 laravel && \
    useradd -u 1000 -ms /bin/bash -g laravel laravel

WORKDIR /var/www/html

USER root

COPY ./docker/php/php.ini /usr/local/etc/php/php.ini
COPY ./docker/scripts/entrypoint.sh /usr/local/bin/entrypoint.sh

RUN chmod +x /usr/local/bin/entrypoint.sh

USER laravel

COPY --chown=laravel:laravel . /var/www/html

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
