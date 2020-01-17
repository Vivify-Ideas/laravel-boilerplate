# Laravel Boilerplate - Laravel 5.7 API

## Requirements

- docker and docker-compose

  > Detailed instructions for setup of docker can be found [here](https://www.docker.com/community-edition).

- user added to `docker` group

# Initial setup of project

## Option 1: Use script to do everything for you

We've written bash script that will do all things that you would normally have to do manually.

In order to run script all you have to do is run `chmod +x init && chmod +x pre-commit && ./init`

> Script might ask you for your password at some point.

## Option 2: Do initial setup manually

### Start docker containers and install dependencies

NOTE: To enter laravel container execute
`docker exec -it laravel /bin/sh`

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

- Generate JWT secret key
  `docker exec -it laravel php artisan jwt:secret`

### Set directory permissions

`sudo chmod -R 777 src/storage src/bootstrap/cache`

# Use custom domain for development

- Edit /etc/hosts add local domain my-project.loc
  `127.0.0.1 my-project.loc`
  > Feel free to replace `my-project.loc` with whatever works for you. In that case you should also change `APP_URL` in .env

# How to verify everything is working ?

- Visit http://localhost/api/documentation and feel free to try API using Swagger UI.

  > In case that you are using custom domain, swap `localhost` with your custom domain.

  > App is available on through `http` port 80

# Health panel details

We are using [Health Panel](https://github.com/antonioribeiro/health) to monitor key aspects of application and services.

- Web access for Health panel is available at `http://localhost/health/panel`
- Health Panel can also return JSON status of each service at route `/health/check`
- Heatlh panel can also be accessed with artisan commands `health:panel` and `health:check`
- To enable Slack notifications change `webhook_url` in `services` config
- For more details about Health Panel go to their [readme](https://github.com/antonioribeiro/health/blob/master/README.md)

# ReCaptcha

If you want to use Google ReCaptcha, you can enabled it in **recaptcha.php** and add **RECAPTCHA_SECRET_KEY** in your .env file. Your configuration would look like this if you enable it:
```
<?php

return [
  'enabled' => true,
  'secret' => env('RECAPTCHA_SECRET_KEY')
];
```
Rule for ReCaptcha is already applied in **UserLoginRequest** and in **UserCreateRequest**.

# Sentry

To configure Sentry, go to the [Sentry](https://sentry.io) and create project for laravel and then just populate **.env** file with your project information:

```
SENTRY_LARAVEL_DSN=https://<key>@sentry.io/<project>
```

# Telescope

[Laravel Telescope](https://laravel.com/docs/5.7/telescope) is included in this boilerplate.

- By default telescope is only available if `APP_ENV` is `local`.
- Telescope is accessible at `/telescope` route.
- Telescope can be disabled globally with ENV variable `TELESCOPE_ENABLED`.
- In order to enable telescope in other environments you have to override [`gate`](https://laravel.com/docs/5.7/telescope#dashboard-authorization) method and remove `isLocal` check from `register` method in `TelescopeServiceProvider`.

# PHP formatter for visual studio code

Install [php formatter](https://marketplace.visualstudio.com/items?itemName=Sophisticode.php-formatter). More [info](https://github.com/Dickurt/vscode-php-formatter/wiki).

# Configure PHP Code Sniffer for Visual Studio Code

- Install [phpcs](https://marketplace.visualstudio.com/items?itemName=ikappas.phpcs).
- Clone [Vivify Ideas Coding Standards](https://github.com/Vivify-Ideas/coding-standard-php) and **make sure folder name is Vivify**
- Open User Settings in VS Code and add `"phpcs.standard": "path_to_Vivify_folder_that_you_just_cloned"`

# Notes for Production/Staging environment

There is separate docker-compose file (`docker-compose.dist.yml`) for production/staging environments.

Biggest differences between dev and staging/production environments are that we are bundling all source files into images, instead of binding local source files to container.

In staging/production environment we included Portainer for easier container/swarm management with Web GUI.

We suggest using our docker registry (`registry.vivifyideas.com`) for those environments, instead of building from Dockerfile

### Exposed services in Production/Staging environment

- Nginx at ports 80/443
- MariaDB at port 3306
- Portainer at port 9000

