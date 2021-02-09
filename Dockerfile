FROM php:7-apache

LABEL mantainer="Rene Bentes Pinto <github.com/renebentes>"

# Enable Apache Rewrite Module
RUN a2enmod rewrite

RUN apt-get update; \
	apt-get upgrade -y; \
	apt-get dist-upgrade -y

# Install PHP extensions
RUN set -ex; \
	\
	savedAptMark="$(apt-mark showmanual)"; \
	\
	apt-get install -y --no-install-recommends \
	libbz2-dev \
	libgmp-dev \
	libjpeg-dev \
	libldap2-dev \
	libmcrypt-dev \
	libmemcached-dev \
	libpng-dev \
	libpq-dev \
	libzip-dev \
	; \
	\
	docker-php-ext-configure gd --with-jpeg; \
	debMultiarch="$(dpkg-architecture --query DEB_BUILD_MULTIARCH)"; \
	docker-php-ext-configure ldap --with-libdir="lib/$debMultiarch"; \
	docker-php-ext-install -j "$(nproc)" \
	bz2 \
	gd \
	gmp \
	ldap \
	mysqli \
	pdo_mysql \
	pdo_pgsql \
	pgsql \
	zip \
	; \
	\
	# pecl will claim success even if one install fails, so we need to perform each install separately
	pecl install APCu-5.1.19; \
	pecl install memcached-3.1.5; \
	pecl install redis-4.3.0; \
	\
	docker-php-ext-enable \
	apcu \
	memcached \
	redis \
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

# Installing Node and dependencies

RUN curl -sSL https://raw.githubusercontent.com/nvm-sh/nvm/v0.37.2/install.sh | bash; \
	. ~/.bashrc; \
	nvm install --lts; \
	\
	npm i -g npm@latest gulp-cli; \
	npm i

EXPOSE 3000
EXPOSE 80

CMD [ "apache2-foreground" ]
