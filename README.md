## PatchDay [![Build Status](https://travis-ci.com/danielschleindlsperger/patch-day.svg?token=VD8yfsWVJBPy7NewnTP6&branch=master)](https://travis-ci.com/danielschleindlsperger/patch-day)

Platform for management of periodic updates of software projects.

### Install & Setup
- clone this project
- `$ cd patch-day`
- `$ composer install && npm install && npm run dev`
- `$ mv .env.example .env`
- set database credentials in the `.env` file
- `$ php artisan key:generate`
- `$ php artisan migrate`
- `$ php artisan db:seed` to populate database for testing
- `$ phpunit`
- Hopefully all green and ready to go :v:

### Build commands
- Development build `$ npm run dev`
- Watcher `$ npm run watch`
- Combine for frontend development: `$ npm run dev && npm run watch`
- Build for production (minify, uglify, strip console.log(), ..): `$ npm run 
production`

### Dependencies

- Backend:
    - Laravel: https://laravel.com
    - Laravel Passport: https://laravel.com/docs/5.4/passport
- Frontend:
    - vuejs: https://vuejs.org/v2/guide/
    - vue-router: https://router.vuejs.org/en/
    - vuetify: https://vuetifyjs.com/
    - axios: https://github.com/mzabriskie/axios