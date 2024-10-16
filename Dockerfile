FROM php:8.3-cli

RUN apt update && \
    apt install -y libzip-dev && \
    pecl install xdebug

RUN docker-php-ext-install zip && \
    docker-php-ext-enable xdebug

RUN useradd -m nonrootuser
USER nonrootuser

COPY --from=composer:2.5 /usr/bin/composer /usr/bin/composer
