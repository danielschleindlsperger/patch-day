<template>
    <v-container>
        <v-row>
            <v-col xs12 lg6>
                <upcoming></upcoming>
            </v-col>
        </v-row>
    </v-container>
</template>

<script>
  import Upcoming from 'components/upcoming-patch-days/Upcoming'
  export default {
    components: {
      Upcoming,
    },
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