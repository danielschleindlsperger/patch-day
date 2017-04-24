function auth(vm) {
  window.axios.interceptors.response.use(function (response) {
    return response;
  }, function (error) {
    // redirect if unauthenticated
    if (error.response.status === 401) {
      vm.$router.push('/login')
    }
    return Promise.reject(error);
  });
}

export default auth;