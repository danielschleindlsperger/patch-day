function auth (vm) {
  window.axios.interceptors.response.use(function (response) {
    return response;
  }, function (error) {
    console.log(error.response.data)
    // redirect if unauthenticated
    if (error.response.status === 401) {
      vm.$router.push('/login')
    }
    return Promise.reject(error);
  });
}

export default auth;