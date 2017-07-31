const {mix} = require('laravel-mix');
const path = require('path');
require('dotenv').config();

mix.webpackConfig({
  resolve: {
    alias: {
      components: path.resolve(__dirname, 'resources/assets/js/components'),
      pages: path.resolve(__dirname, 'resources/assets/js/pages'),
      mixins: path.resolve(__dirname, 'resources/assets/js/mixins'),
      repository: path.resolve(__dirname, 'resources/assets/js/repository'),
    }
  }
});

mix
  .js('resources/assets/js/admin.js', 'public/js')
  .js('resources/assets/js/client.js', 'public/js')
  .sourceMaps()
  .copy('node_modules/vuetify/dist/vuetify.min.css', 'public/css/app.css')
  .copy('node_modules/bootstrap/dist/css/bootstrap.min.css', 'public/css')
  .browserSync({
    proxy: process.env.APP_URL
  });
