<template>
    <v-container>
        <v-card>
            <v-card-text>
                {{ $route.params.id }}
            </v-card-text>
        </v-card>
    </v-container>
</template>

<script>
  export default {
    data() {
      return {
        company: {}
      }
    },
    mounted() {
      const companyId = this.$route.params.id
      this.$http.get(`/companies/${companyId}`)
        .then(response => {
          this.company = response.data
          console.log(response.data)
        })
        .catch(error => {
          error.response.data
        })
    }
  }
</script>

<style scoped>

</style>