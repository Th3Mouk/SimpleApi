# the different stages of this Dockerfile are meant to be built into separate images
# https://docs.docker.com/develop/develop-images/multistage-build/#stop-at-a-specific-build-stage
# https://docs.docker.com/compose/compose-file/#target

# https://docs.docker.com/engine/reference/builder/#understand-how-arg-and-from-interact
ARG PHP_VERSION=8.1

# "php" stage
FROM php:${PHP_VERSION}-fpm AS php_fpm

RUN set -eux; \
    apt-get update; \
	apt-get install -y \
		$PHPIZE_DEPS \
		acl \
		libicu-dev \
		libpq-dev \
		libzip-dev \
		zip \
	; \
	\
	docker-php-ext-configure zip; \
	docker-php-ext-install -j$(nproc) \
		intl \
		pgsql \
		pdo_pgsql \
		zip \
	;

###> recipes ###

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

COPY docker/php/zz-docker.conf /usr/local/etc/php-fpm.d/zz-docker.conf

VOLUME /var/run/php

# https://getcomposer.org/doc/03-cli.md#composer-allow-superuser
ENV COMPOSER_ALLOW_SUPERUSER=1
ENV PATH="${PATH}:/root/.composer/vendor/bin"

COPY docker/php/docker-healthcheck.sh /usr/local/bin/docker-healthcheck
RUN chmod +x /usr/local/bin/docker-healthcheck

HEALTHCHECK --interval=10s --timeout=3s --retries=3 CMD ["docker-healthcheck"]

COPY docker/php/docker-entrypoint.sh /usr/local/bin/docker-entrypoint
RUN chmod +x /usr/local/bin/docker-entrypoint

ENV SYMFONY_PHPUNIT_VERSION=9

ENTRYPOINT ["docker-entrypoint"]
CMD ["php-fpm"]

# build for development
FROM php_fpm as php_fpm_dev

RUN ln -s $PHP_INI_DIR/php.ini-development $PHP_INI_DIR/php.ini;
#COPY docker/php/php.dev.ini $PHP_INI_DIR/conf.d/php-custom.ini

ARG XDEBUG_VERSION=3.1.2
RUN set -eux; \
 pecl install xdebug-$XDEBUG_VERSION; \
 docker-php-ext-enable xdebug

WORKDIR /app

# build for production
FROM php_fpm as php_fpm_prod

ARG APP_ENV=prod

WORKDIR /app

RUN ln -s $PHP_INI_DIR/php.ini-production $PHP_INI_DIR/php.ini
#COPY docker/php/php.prod.ini $PHP_INI_DIR/conf.d/php-custom.ini

# prevent the reinstallation of vendors at every changes in the source code
COPY composer.json composer.lock symfony.lock ./
RUN set -eux; \
	composer install --prefer-dist --no-dev --no-scripts --no-progress; \
	composer clear-cache

# copy only specifically what we need
COPY .env ./
COPY bin bin/
COPY config config/
COPY public public/
COPY src src/

RUN set -eux; \
	mkdir -p var/cache var/log; \
	composer dump-autoload --classmap-authoritative --no-dev; \
	composer dump-env prod; \
	composer run-script --no-dev post-install-cmd; \
	chmod +x bin/console; sync

VOLUME /app/var
