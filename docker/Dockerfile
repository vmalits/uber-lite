FROM php:8.4-cli-alpine

RUN apk add --no-cache \
    postgresql-dev libzip-dev zip unzip git curl supervisor \
    autoconf g++ make linux-headers \
    gd-dev libjpeg-turbo-dev libpng-dev libwebp-dev \
    freetype-dev zlib-dev libgcc \
    optipng jpegoptim gifsicle pngquant nodejs npm

RUN npm install -g svgo@3

RUN docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
  && docker-php-ext-install -j$(nproc) pdo pdo_pgsql zip bcmath sockets gd

RUN pecl install redis igbinary msgpack \
  && docker-php-ext-enable redis igbinary msgpack

RUN echo 'memory_limit = 4G' > /usr/local/etc/php/conf.d/memory.ini

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

COPY composer.json composer.lock ./
RUN composer install --optimize-autoloader --no-interaction --no-scripts

COPY . .
RUN composer run-script post-autoload-dump

RUN addgroup -g 1000 www && adduser -D -u 1000 -G www www \
  && chown -R www:www /var/www

USER www

COPY --from=ghcr.io/roadrunner-server/roadrunner:2025.1.6 /usr/bin/rr /usr/local/bin/rr

EXPOSE 8080

CMD ["rr", "serve", "-c", ".rr.yaml"]
