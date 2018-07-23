# Laravel Boilerplate - Laravel 5.6 API

## Requirements

- docker and docker-compose

  > Detailed instructions for setup of docker can be found [here](https://www.docker.com/community-edition).

- user added to `docker` group

# Initial setup of project

## Option 1: Use script to do everything for you

We've written bash script that will do all things that you would normally have to do manually.

In order to run script all you have to do is run `chmod +x init && ./init`

> Script might ask you for your password at some point.

## Option 2: Do initial setup manually

### Start docker containers and install dependencies

- Run docker containers in detached mode
  `docker-compose up -d`

  > Whole stack can be stopped with `docker-compose stop`

- Check all service status:
  `docker-compose ps`

- Install project dependencies with composer in container
  `docker exec -it laravel composer install`

- Generate application key
  `docker exec -it laravel php artisan key:generate`

### Create local environments

- Copy content from .env.example in .env (`cp .env.example .env`)

- Install/Setup Passport by running (read more on https://laravel.com/docs/5.6/passport#introduction)
  `docker exec -it laravel php artisan migrate`
  `docker exec -it laravel php artisan passport:install`

### Set directory permissions

`sudo chmod -R 777 src/storage src/bootstrap/cache`

# Use custom domain for development

- Edit /etc/hosts add local domain my-project.loc
  `127.0.0.1 my-project.loc`
  > Feel free to replace `my-project.loc` with whatever works for you. In that case you should also change `APP_URL` in .env

# How to verify everything is working ?

- Visit http://localhost/api/documentation and feel free to try API using Swagger UI.

  > In case that you are using custom domain, swap `localhost` with your custom domain.

  > App is available on both `http` and `https` (keep in mind that https is using self-signed certificates)

# PHP formatter for visual studio code

Install [php formatter](https://marketplace.visualstudio.com/items?itemName=Sophisticode.php-formatter). More [info](https://github.com/Dickurt/vscode-php-formatter/wiki).

# Notes for Production/Staging environment

There is separate docker-compose file (`docker-compose.dist.yml`) for production/staging environments.

Biggest differences between dev and staging/production environments are that we are bundling all source files into images, instead of binding local source files to container.

In staging/production environment we included Portainer for easier container/swarm management with Web GUI.

We suggest using our docker registry (`registry.vivifyideas.com`) for those environments, instead of building from Dockerfile

### Exposed services in Production/Staging environment

- Nginx at ports 80/443
- MariaDB at port 3306
- Portainer at port 9000
