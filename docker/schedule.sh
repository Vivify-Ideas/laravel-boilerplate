#! /bin/sh

cd /app

chown -R www-data:www-data /app/storage/app
chown -Rv www-data:www-data /app/bootstrap/cache

php artisan cache:clear
php artisan config:cache

while true; do
	sleep 60
	php artisan schedule:run
done

