<template>
    <v-container>
        <v-layout>
            <v-flex xs12 lg6>
                Client Dashboard
            </v-flex>
            <v-flex xs12 lg6>
            </v-flex>
        </v-layout>
    </v-container>
</template>

<script>
  import eventBus from 'components/event-bus'

  export default {
    mounted() {
      eventBus.$emit('page.loading', false)
      this.$http.get('/users/me')
        .then(response => {
          this.$root.user = response.data
        })
        .catch(error => {
          console.error(error.response.data)
        });
    }
  }
</script>