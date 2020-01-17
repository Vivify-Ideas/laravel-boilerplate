FROM vivifyideas/php-fpm-production-docker-alpine:7.4

COPY ./docker/custom.ini /usr/local/etc/php/conf.d/custom.ini
COPY ./docker/schedule.sh /scripts/schedule.sh

WORKDIR /app
COPY ./src /app

RUN set -ex && \
	composer install --no-dev --no-scripts && \
	chown -R www-data:www-data /app/storage /app/bootstrap/cache

