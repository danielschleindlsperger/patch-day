const { mix } = require('laravel-mix');
require('dotenv').config();

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix
  .js('resources/assets/js/app.js', 'public/js')
  .sourceMaps()
  .copy('node_modules/vuetify/dist/vuetify.min.css', 'public/css/app.css')
  .browserSync({
    proxy: process.env.APP_URL
  });
