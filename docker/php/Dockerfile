FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    zip unzip curl git libpq-dev libzip-dev \
    libpng-dev libonig-dev libxml2-dev libssl-dev \
    && docker-php-ext-install pdo pdo_mysql zip gd mbstring

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

CMD ["php-fpm"]