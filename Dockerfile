FROM composer:latest AS composer
FROM php:8.3.8-cli-alpine
LABEL Maintainer="Marcelo Motta <marcelo.motta@sp.agence.com.br>"
COPY --from=composer /usr/bin/composer /usr/bin/composer

ENV TZ=America/Bahia
ARG WORKDIR=/application
WORKDIR $WORKDIR

# Setup user
ARG UNAME=app_user
ARG UID=1000
ARG GID=1000
RUN addgroup -g $GID ${UNAME}
RUN adduser -D -H -u $UID -G ${UNAME} -s /bin/sh ${UNAME}

# Install packages
RUN apk --no-cache add supervisor python3 tzdata && \
    docker-php-ext-install pdo pdo_mysql && \
    docker-php-ext-enable pdo_mysql

    
COPY php.ini /usr/local/etc/php/conf.d/custom.ini

# Configure supervisord
COPY supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Configure Crunz
COPY crunz-example.yml crunz.yml

# Configure cron
RUN crontab -l | { cat; echo "* * * * * cd $WORKDIR && vendor/bin/crunz schedule:run >> /dev/null 2>&1"; } | crontab -

COPY --chown=$UNAME . "$WORKDIR"

RUN composer install --no-dev

CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]