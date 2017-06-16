import Vue from 'vue'
import VueRouter from 'vue-router'
import Vuetify from 'vuetify'
import axios from 'axios'

import routes from './routes.js'
import auth from './auth.js'

Vue.prototype.$http = axios;
Vue.component('app', require('./Client.vue'));
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
})

app.$http.defaults.headers.common = {
  'X-XSRF-TOKEN': window.Laravel.csrfToken,
  'X-Requested-With': 'XMLHttpRequest',
  'Accept': 'application/json'
};
auth(app)

app.$mount('#app');