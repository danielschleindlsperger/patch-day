<template>
    <v-card>
        <v-card-row class="primary">
            <v-card-title>
                <span class="white--text">Upcoming PatchDays</span>
            </v-card-title>
        </v-card-row>
        <v-card-text>
            <v-list>
                <v-list-item v-for="protocol in protocols"
                             :key="protocol.id">
                    <v-list-tile router avatar
                                 :href="'/protocols/' + protocol.id">
                        <v-list-tile-avatar>
                            <img src="/img/company_dummy.png"/>
                        </v-list-tile-avatar>
                        <v-list-tile-content>
                            <v-list-tile-title>
                                {{ protocol.patch_day.project.name }}
                            </v-list-tile-title>
                            <v-list-tile-sub-title>
                                <!-- Display in days from now aswell -->
                                Due on {{ protocol.due_date | Date }}
                                ({{ protocol.due_date | DaysFromNow }})
                            </v-list-tile-sub-title>
                        </v-list-tile-content>
                        <v-list-tile-action>
                            <v-icon>navigate_next</v-icon>
                        </v-list-tile-action>
                    </v-list-tile>
                </v-list-item>
            </v-list>
        </v-card-text>
    </v-card>
</template>

<script>
  import filters from 'mixins/filters'
  export default {
    name: 'upcoming',
    mixins: [filters],
    data() {
      return {
        protocols: {},
      }
    },
    mounted() {
      this.$http.get('/protocols/upcoming')
        .then(response => {
          this.protocols = response.data
          console.log(response.data)
        })
        .catch(error => {
          console.error(error)
          eventBus.$emit('info.snackbar', error.response.data.error)
          if (error.response.status === 404) {
            this.$router.push({name: 'not-found'})
          }
        })
    },
  }
</script>