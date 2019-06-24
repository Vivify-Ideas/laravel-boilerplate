FROM vivifyideas/php-fpm-production-docker-alpine

COPY ./docker/custom.ini /usr/local/etc/php/conf.d/custom.ini

WORKDIR /app
COPY ./src /app

RUN set -ex && \
	composer install --no-dev --no-scripts && \
	chown -R www-data:www-data /app/storage /app/bootstrap/cache

