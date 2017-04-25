<template>
    <div>DASHBOARD PAGE</div>
</template>

<script>
  export default {
    mounted() {
      this.$http.get('/users/me')
        .then(response => {
          this.$root.user = response.data
          console.log(response.data);
        })
        .catch(error => {
          console.log(error.response.data)
        });
    }
  }
</script>