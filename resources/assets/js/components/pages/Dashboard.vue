<template>
    <div>DASHBOARD PAGE</div>
</template>

<script>
  export default {
    mounted() {
      this.$http.get('/projects')
        .then(response => {
          console.log(response.data);
        });
    }
  }
</script>