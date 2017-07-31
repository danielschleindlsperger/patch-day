<template>
    <v-card>
        <v-card-title primary-title class="primary pa-4">
            <h2 class="title white--text ma-0">Upcoming PatchDays</h2>
        </v-card-title>
        <v-card-text>
            <v-list>
                <v-list-tile avatar v-for="patch_day in patch_days"
                             :key="patch_day.id"
                             :to="'/patch-days/' + patch_day.id">
                    <v-list-tile-content>
                        <v-list-tile-title>
                            PatchDay #{{ patch_day.id}}
                        </v-list-tile-title>
                        <v-list-tile-sub-title>
                            <!-- Display in days from now aswell -->
                            On {{ patch_day.date | Date }}
                            ({{ patch_day.date | HumanizeDate }})
                        </v-list-tile-sub-title>
                    </v-list-tile-content>
                    <v-list-tile-action>
                        <v-icon>navigate_next</v-icon>
                    </v-list-tile-action>
                </v-list-tile>
            </v-list>
        </v-card-text>
    </v-card>
</template>

<script>
  import filters from 'mixins/filters'
  import eventBus from 'components/event-bus'
  export default {
    name: 'upcoming',
    mixins: [filters],
    data() {
      return {
        patch_days: {},
      }
    },
    mounted() {
      this.$http.get('/patch-days/upcoming')
        .then(response => {
          this.patch_days = response.data
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