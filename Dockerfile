FROM vivifyideas/php-fpm-production

# Install mysql client
RUN apt-get update && apt-get install -y mysql-client

# Set working directory
WORKDIR /app

# Copy all files to container
COPY ./src /app

# Copy custom.ini
COPY ./configs/custom.ini /usr/local/etc/php/conf.d/custom.ini

# Install dependencies
RUN composer install --no-dev --no-scripts

# Chown storage and boostrap cache as www-data user/group
RUN chown -R www-data:www-data \
    /app/storage \
    /app/bootstrap/cache \
    /app/public/uploads