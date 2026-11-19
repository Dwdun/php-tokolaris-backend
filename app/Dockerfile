FROM php:8.2-fpm-alpine

RUN apk update && apk add --no-cache \
    curl \
    git \
    mariadb-client \
    linux-headers \
    bash \
    $PHPIZE_DEPS \
    && pecl install redis \
    && docker-php-ext-enable redis \
    && docker-php-ext-install pdo pdo_mysql

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# Salin konfigurasi dependensi dan pre-install ke /opt/vendor
COPY composer.json /opt/composer.json
RUN cd /opt && composer install --no-interaction --prefer-dist --no-scripts || true

# Entrypoint handler
COPY app/docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

EXPOSE 9000
ENTRYPOINT ["docker-entrypoint.sh"]
CMD ["php-fpm"]
