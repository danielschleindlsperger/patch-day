## PatchDay

Platform for management of periodic updates of software projects.

### Install & Setup
- clone this project
- `$ cd patchday`
- `$ composer install`
- `$ mv .env.example .env`
- set database credentials
- `$ php artisan key:generate`
- `$ php artisan migrate`
- `$ php artisan passport:install` to generate the keys for OAuth Authentication
- `$ phpunit`
- Hopefully all green and ready to go :v:
