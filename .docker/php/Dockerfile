FROM php:7-fpm

LABEL author="Rene Bentes Pinto <github.com/renebentes>"
LABEL name=joomlagov/php
LABEL version=1.0.0

# Install PHP extensions
RUN savedAptMark="$(apt-mark showmanual)"; \
    \
    apt-get update; \
    \
    apt-get install -y --no-install-recommends \
        libbz2-dev \
        libgmp-dev \
        libjpeg-dev \
        libmcrypt-dev \
        libmemcached-dev \
        libpng-dev \
        libpq-dev \
        libzip-dev \
    ; \
    \
    docker-php-ext-configure gd --with-jpeg; \
    docker-php-ext-install -j "$(nproc)" \
        bz2 \
        gd \
        gmp \
        mysqli \
        pdo_mysql \
        zip \
    ; \
    \
    # reset apt-mark's "manual" list so that "purge --auto-remove" will remove all build dependencies
    apt-mark auto '.*' > /dev/null; \
    apt-mark manual $savedAptMark; \
    ldd "$(php -r 'echo ini_get("extension_dir");')"/*.so \
        | awk '/=>/ { print $3 }' \
        | sort -u \
        | xargs -r dpkg-query -S \
        | cut -d: -f1 \
        | sort -u \
        | xargs -rt apt-mark manual; \
    \
    apt-get purge -y --auto-remove -o APT::AutoRemove::RecommendsImportant=false; \
    rm -rf /var/lib/apt/lists/*

CMD ["php-fpm"]
