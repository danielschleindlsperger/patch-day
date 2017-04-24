function auth(vm) {
  vm.$http.interceptors.response.use(function (response) {
    return response;
  }, function (error) {
    console.log('asfasdf')
    // redirect if unauthenticated
    if (error.response.status === 401) {
      console.log('teasdfsafd', error.response.data)
      vm.user = {};
      vm.$router.push('/login')
    }
    return Promise.reject(error);
  });
}

export default auth;