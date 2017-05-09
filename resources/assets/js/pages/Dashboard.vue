<template>
    <v-container>
        <v-row>
            <v-col xs12 lg6>
                <upcoming></upcoming>
            </v-col>
            <v-col xs12 lg6>
                <quick-create></quick-create>
            </v-col>
        </v-row>
    </v-container>
</template>

<script>
  import Upcoming from 'components/upcoming-patch-days/Upcoming'
  import QuickCreate from 'components/quick-create/QuickCreate'
  export default {
    components: {
      Upcoming,
      QuickCreate,
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