<h1 align="center">Deployment/Setup</h1>

## Deployment steps

- Setup a LAMP(Linux, Apache, Mysql, Php)/LEMP(Linux, Nginx, Mysql, Php) server.
- Install git and composer on the system.
- Clone the git repository in the /var/www/html
- Run ```composer install```
- Copy the .env.example to .env files and update the .env files with the necessary environmental variables.
- Run migrations using command ```php artisan migrate```
- Run ```php artisan config:cache```
- Create a virtualhost and point it to the public folder of the project repository.

