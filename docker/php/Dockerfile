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
    && docker-php-ext-install -j$(nproc) bcmath gd mbstring pcntl pdo_mysql zip \
    && pecl install redis xdebug-3.4.2 \
    && docker-php-ext-enable redis xdebug \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin --filename=composer \
    && mkdir -p /etc/apt/keyrings \
    && curl -fsSL https://deb.nodesource.com/gpgkey/nodesource-repo.gpg.key | gpg --dearmor -o /etc/apt/keyrings/nodesource.gpg \
    && echo "deb [signed-by=/etc/apt/keyrings/nodesource.gpg] https://deb.nodesource.com/node_23.x nodistro main" > /etc/apt/sources.list.d/nodesource.list \
    && apt-get update && apt-get install -y --no-install-recommends nodejs \
    && corepack enable \
    && corepack prepare pnpm@10.9.0 --activate \
    && npm cache clean --force \
    && pnpm store prune \
    && apt-get autoremove -y && apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

RUN groupadd -g 1000 laravel && useradd -u 1000 -ms /bin/bash -g laravel laravel

WORKDIR /var/www/html

COPY ./docker/php/php.ini /usr/local/etc/php/php.ini
COPY ./docker/supervisor/supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY ./docker/ssl/rootCA.pem /usr/local/share/ca-certificates/rootCA.crt

RUN update-ca-certificates

RUN mkdir -p /var/log/supervisor && chown laravel:laravel /var/log/supervisor

USER laravel

COPY --chown=laravel:laravel . /var/www/html

CMD ["supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
