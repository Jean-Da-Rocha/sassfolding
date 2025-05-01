ARG PHP_VERSION=8.4

FROM php:${PHP_VERSION}-fpm

ARG COMPOSER_VERSION
ARG GID
ARG GROUP_NAME
ARG NODE_VERSION
ARG PNPM_VERSION
ARG UID
ARG USER_NAME
ARG XDEBUG_VERSION

RUN apt-get update && apt-get install -y --no-install-recommends \
        curl \
        git \
        gnupg \
        libonig-dev \
        libpng-dev \
        libxml2-dev \
        libzip-dev \
        supervisor \
        unzip \
    && docker-php-ext-install -j$(nproc) bcmath gd mbstring pcntl pdo_mysql zip \
    && pecl install redis xdebug-${XDEBUG_VERSION} \
    && docker-php-ext-enable redis xdebug \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin --filename=composer --version=$COMPOSER_VERSION \
    && mkdir -p /etc/apt/keyrings \
    && curl -fsSL https://deb.nodesource.com/gpgkey/nodesource-repo.gpg.key | gpg --dearmor -o /etc/apt/keyrings/nodesource.gpg \
    && echo "deb [signed-by=/etc/apt/keyrings/nodesource.gpg] https://deb.nodesource.com/node_$NODE_VERSION.x nodistro main" > /etc/apt/sources.list.d/nodesource.list \
    && apt-get update && apt-get install -y --no-install-recommends nodejs \
    && corepack enable \
    && corepack prepare pnpm@$PNPM_VERSION --activate \
    && npm cache clean --force \
    && pnpm store prune \
    && apt-get autoremove -y && apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

RUN groupadd -g $GID $GROUP_NAME && useradd -u $UID -ms /bin/bash -g $GROUP_NAME $USER_NAME

WORKDIR /var/www/html

COPY ./docker/hybridly/conf.d/ /etc/supervisor/conf.d/
COPY ./docker/hybridly/config/php.ini /usr/local/etc/php/php.ini
COPY ./docker/hybridly/ssl/rootCA.pem /usr/local/share/ca-certificates/rootCA.crt
COPY ./docker/hybridly/supervisord.conf /etc/supervisor/supervisord.conf

RUN update-ca-certificates
RUN mkdir -p /var/log/supervisor && chown laravel:laravel /var/log/supervisor

USER $USER_NAME

COPY --chown=$USER_NAME:$GROUP_NAME ../.. /var/www/html

CMD ["supervisord", "-c", "/etc/supervisor/supervisord.conf"]
