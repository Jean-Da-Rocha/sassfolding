FROM php:8.4-fpm

RUN apt-get update && apt-get install -y \
    unzip \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    zip \
    git && \
    docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

RUN curl -fsSL https://deb.nodesource.com/setup_23.x | bash - && \
    apt-get install -y nodejs && \
    npm install -g npm@latest && \
    npm install -g pnpm

RUN groupadd -g 1000 laravel && \
    useradd -u 1000 -ms /bin/bash -g laravel laravel

USER laravel

WORKDIR /var/www/html

COPY php.ini /usr/local/etc/php/php.ini

COPY --chown=laravel:laravel . /var/www/html

EXPOSE 9000
EXPOSE 5173

CMD ["sh", "-c", "php-fpm & pnpm run dev"]
