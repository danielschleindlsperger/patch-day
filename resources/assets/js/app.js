import Vue from 'vue'
import VueRouter from 'vue-router'
import Vuetify from 'vuetify'
import VueCookie from 'vue-cookie'
import axios from 'axios'

import routes from './routes.js'
import auth from './auth.js'

window.axios = axios
window.axios.defaults.headers.common = {
  'X-Requested-With': 'XMLHttpRequest',
  'Accept': 'application/json'
};

Vue.component('app', require('./components/App.vue'));

Vue.use(VueRouter)
Vue.use(Vuetify)
Vue.use(VueCookie)

const router = new VueRouter({
  routes
})

const app = new Vue({
  data() {
    return {
      auth: {
        loggedIn: false,
        user: {}
      }
    }
  },
  router,
}).$mount('#app');

auth(app)
