<template>
    <div>DASHBOARD PAGE</div>
</template>

<script>
  export default {
    mounted() {
      window.axios.get('/projects')
        .then(response => {
          console.log(response.data);
        });
    }
  }
</script>