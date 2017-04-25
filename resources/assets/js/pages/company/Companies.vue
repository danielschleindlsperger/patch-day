<template>
    <v-card>
        <v-card-text>
            <v-list>
                <v-list-item v-for="item in list" :key="item.id">
                    <v-list-tile avatar>
                        <v-list-tile-avatar>
                            <v-icon>business</v-icon>
                        </v-list-tile-avatar>
                        <v-list-tile-content>
                            <v-list-tile-title>{{ item.name }}
                            </v-list-tile-title>
                        </v-list-tile-content>
                        <v-list-tile-action>
                            <v-btn icon ripple>
                                <v-icon class="grey--text text--lighten-1">
                                    info
                                </v-icon>
                            </v-btn>
                        </v-list-tile-action>
                    </v-list-tile>
                </v-list-item>
            </v-list>
        </v-card-text>
    </v-card>
</template>

<script>
  export default {
    data() {
      return {
        list: []
      }
    },
    mounted() {
      this.$http.get('/companies')
        .then(response => {
          this.list = response.data
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