FROM node:22-alpine AS assets
WORKDIR /app
COPY package*.json ./
RUN npm ci
COPY resources ./resources
COPY vite.config.js ./vite.config.js
COPY public ./public
RUN npm run build

FROM composer:2 AS dependencies
WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-interaction --prefer-dist --no-progress --no-scripts --optimize-autoloader

FROM php:8.3-cli-alpine AS app
RUN set -e; apk add --no-cache --virtual .build-deps $PHPIZE_DEPS postgresql-dev oniguruma-dev; docker-php-ext-install mbstring pdo_pgsql; apk add --no-cache libpq; apk del .build-deps
WORKDIR /var/www/html
COPY . .
COPY --from=dependencies /app/vendor ./vendor
COPY --from=assets /app/public/build ./public/build
RUN set -e; mkdir -p storage/framework/cache storage/framework/sessions storage/framework/views storage/logs bootstrap/cache; chown -R www-data:www-data storage bootstrap/cache; chmod -R ug+rwx storage bootstrap/cache
COPY docker/entrypoint.sh /usr/local/bin/entrypoint
EXPOSE 8080
CMD ["sh", "/usr/local/bin/entrypoint"]