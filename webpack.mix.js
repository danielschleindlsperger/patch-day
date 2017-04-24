const {mix} = require('laravel-mix');
const path = require('path');
require('dotenv').config();

mix.webpackConfig({
  resolve: {
    alias: {
      components: path.resolve(__dirname, 'resources/assets/js/components'),
      pages: path.resolve(__dirname, 'resources/assets/js/pages'),
    }
  }
});

mix
  .js('resources/assets/js/app.js', 'public/js')
  .sourceMaps()
  .copy('node_modules/vuetify/dist/vuetify.min.css', 'public/css/app.css')
  .browserSync({
    proxy: process.env.APP_URL
  });
