FROM php:8.4-cli-alpine

# System deps
RUN apk add --no-cache \
    postgresql-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    curl \
    supervisor \
    autoconf \
    g++ \
    make \
    linux-headers

# Core PHP extensions
RUN docker-php-ext-install \
    pdo \
    pdo_pgsql \
    zip \
    bcmath \
    sockets

# Redis stack
RUN pecl install redis igbinary msgpack \
    && docker-php-ext-enable redis igbinary msgpack

# Increase memory limit
RUN echo 'memory_limit = 4G' > /usr/local/etc/php/conf.d/memory.ini

# Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

COPY composer.json composer.lock ./
RUN composer install --optimize-autoloader --no-interaction --no-scripts

COPY . .
RUN composer run-script post-autoload-dump

# Non-root user
RUN addgroup -g 1000 www && adduser -D -u 1000 -G www www
RUN chown -R www:www /var/www
USER www

# RoadRunner
COPY --from=ghcr.io/roadrunner-server/roadrunner:2025.1.6 /usr/bin/rr /usr/local/bin/rr

EXPOSE 8080
CMD ["rr", "serve", "-c", ".rr.yaml"]
