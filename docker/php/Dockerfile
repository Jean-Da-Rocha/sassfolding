FROM php:8.4-fpm

RUN apt-get update && apt-get install -y --no-install-recommends \
    curl \
    git \
    gnupg \
    libonig-dev \
    libpng-dev \
    libxml2-dev \
    libzip-dev \
    supervisor \
    && docker-php-ext-install bcmath exif gd mbstring pcntl pdo_mysql zip \
    && pecl install redis xdebug-3.4.2 \
    && docker-php-ext-enable redis xdebug

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN curl -fsSL https://deb.nodesource.com/setup_23.x | bash - \
    && apt-get install -y --no-install-recommends nodejs \
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

RUN mkdir -p /var/log/supervisor && chown laravel:laravel /var/log/supervisor

COPY ./docker/php/php.ini /usr/local/etc/php/php.ini
COPY ./docker/supervisor/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

COPY ./docker/ssl/rootCA.pem /usr/local/share/ca-certificates/rootCA.crt

RUN update-ca-certificates

USER laravel

COPY --chown=laravel:laravel . /var/www/html

CMD ["supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
