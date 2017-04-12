## PatchDay

Platform for management of periodic updates of software projects.

### Install & Setup
- clone this project
- `$ cd patch-day`
- `$ composer install`
- `$ mv .env.example .env`
- set database credentials in the `.env` file
- `$ php artisan key:generate`
- `$ php artisan migrate`
- `$ php artisan passport:install` to generate the keys for OAuth Authentication
- `$ phpunit`
- Hopefully all green and ready to go :v:
