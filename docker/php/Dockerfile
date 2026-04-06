FROM composer:2 AS vendor

WORKDIR /app

COPY composer.json composer.lock symfony.lock ./
RUN composer install \
    --no-dev \
    --no-interaction \
    --prefer-dist \
    --optimize-autoloader \
    --no-scripts

FROM php:8.3-cli-alpine AS app

RUN apk add --no-cache curl libpq-dev \
    && docker-php-ext-install pdo_pgsql

WORKDIR /app

COPY --from=vendor /app/vendor /app/vendor
COPY . .

EXPOSE 8000

CMD ["sh", "-c", "php -S 0.0.0.0:${APP_PORT:-8000} -t public"]
