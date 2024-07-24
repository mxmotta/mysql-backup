FROM composer:latest AS composer
FROM php:8.3.8-cli-alpine
LABEL Maintainer="Marcelo Motta <marcelo.motta@sp.agence.com.br>"
COPY --from=composer /usr/bin/composer /usr/bin/composer
WORKDIR "/application"

COPY . "/application"

RUN composer install --no-dev

CMD [ "php",  "index.php"]