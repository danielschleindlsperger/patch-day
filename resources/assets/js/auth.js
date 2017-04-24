function auth(vm) {

  window.axios.interceptors.request.use(function (config) {
    // update laravel request token
    config.headers['X-CSRF-TOKEN'] = window.Laravel.csrfToken
    return config;
  }, function (error) {
    // Do something with request error
    return Promise.reject(error);
  });

  window.axios.interceptors.response.use(function (response) {
    console.log(vm.$cookie.get('XSRF-TOKEN'))
    window.Laravel.csrfToken = vm.$cookie.get('XSRF-TOKEN');
    return response;
  }, function (error) {
    window.Laravel.csrfToken = vm.$cookie.get('XSRF-TOKEN');
    console.log(error.response.data)
    // redirect if unauthenticated
    if (error.response.status === 401) {
      vm.$router.push('/login')
    }
    return Promise.reject(error);
  });
}

export default auth;