import Vue from 'vue'
import VueRouter from 'vue-router'
import Vuetify from 'vuetify'
import axios from 'axios'

import routes from './routes.js'
import auth from './auth.js'

window.axios = axios
window.axios.defaults.headers.common = {
  'X-XSRF-TOKEN': window.Laravel.csrfToken,
  'X-Requested-With': 'XMLHttpRequest',
  'Accept': 'application/json'
};

Vue.component('app', require('./components/App.vue'));

Vue.use(VueRouter)
Vue.use(Vuetify)

const router = new VueRouter({
  routes
})

const app = new Vue({
  data() {
    return {
      user: {}
    }
  },
  router,
}).$mount('#app');

auth(app)
