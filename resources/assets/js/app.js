import Vue from 'vue'
import VueRouter from 'vue-router'
import Vuetify from 'vuetify'
import axios from 'axios'

import routes from './routes.js'

axios.defaults.headers.common = {
  'X-CSRF-TOKEN': window.Laravel.csrfToken,
  'X-Requested-With': 'XMLHttpRequest'
};

Vue.component('app', require('./components/App.vue'));

Vue.use(VueRouter)
Vue.use(Vuetify)

const router = new VueRouter({
  routes
})

const app = new Vue({
  router
}).$mount('#app');
