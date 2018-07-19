# Xtra backend - Laravel 5.6 API

## Requirements

* see laravel 5.6 requirements at: https://laravel.com/docs/5.6/installation#server-requirements
* Docker and DockerCompose (for local development only) (Mac distribution https://download.docker.com/mac/stable/Docker.dmg)

## Local development reference using DockerCompose

* How can I change name of the **my_project**?
You should change **my_project** to your project name in docker-compose file and then change in .env DB_NAME to the name of your database container
You also can change **my-project.loc** in `docker/nginx/site.conf` and then change in .env `APP_URL` to your url

* Edit /etc/hosts add local domain my-project.loc  
`127.0.0.1    my-project.loc`

* Run docker containers in detached mode  
`docker-compose up -d`

(note. If you wish to turn it off, please execute `docker-compose stop`)

* Check all service status:  
`docker-compose ps`

* Log into PHP service container and complete Laravel installation process  
`docker exec -it my_project_php7 bash`

* Inside PHP container, switch to local user **laravel** and install Composer dependencies  
`su laravel`  
`composer install`

## Create local environments

* create .env environment file in project root
* copy content from .env.example in .env

* Install/setup Passport by running (read more on https://laravel.com/docs/5.6/passport#introduction)
(you should be still in PHP container. If not please login into PHP service container first!)  
`php artisan migrate`  
`php artisan passport:install`

* Visit http://my-project.loc/api/documentation and feel free to try API using Swagger UI.

## set directory permissions

`run sudo chmod -R 777 storage bootstrap/cache` 

## php formatter for visual studio code

install [php formatter](https://marketplace.visualstudio.com/items?itemName=Sophisticode.php-formatter). More [info](https://github.com/Dickurt/vscode-php-formatter/wiki).

