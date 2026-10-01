FROM php:8.4-fpm

WORKDIR /var/www/html

RUN apt-get update \
    && apt-get install -y --no-install-recommends libpq-dev \
    && docker-php-ext-install pdo_pgsql \
    && rm -rf /var/lib/apt/lists/*

COPY . /var/www/html

RUN chown -R www-data:www-data /var/www/html