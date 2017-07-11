<template>
    <v-dialog v-model="isOpen" width="640">

        <v-card>
            <v-card-title class="pa-4">
                <h2 class="title ma-0">Cancel PatchDay</h2>
            </v-card-title>

            <v-card-text>
                <v-select
                        :items="patch_days"
                        v-model="patch_day"
                        item-value="id"
                        item-text="name"
                        label="Select PatchDay"
                        return-object
                        light
                        single-line
                        auto
                >
                </v-select>

                <v-card-actions>
                    <v-spacer></v-spacer>

                    <v-btn class="green--text darken-1" flat="flat"
                           @click.native="isOpen = false">
                        Close
                    </v-btn>

                    <v-btn class="red--text darken-1" flat="flat"
                           @click.native="cancel($event)">
                        Cancel PatchDay
                    </v-btn>
                </v-card-actions>
            </v-card-text>

        </v-card>
    </v-dialog>
</template>

<script>
  import eventBus from 'components/event-bus'
  import filters from 'mixins/filters'

  export default {
    name: 'patch-day-cancel-modal',
    mixins: [filters],
    data() {
      return {
        isOpen: false,
        project: {},
        patch_day: {},
        patch_days: [],
      }
    },
    mounted () {
      eventBus.$on('patch_day_cancel.view.modal', (project) => {
        this.project = project
        this.getPatchDays()
        this.isOpen = true
      })
    },
    methods: {
      getPatchDays() {
        this.$http.get(`/projects/${this.project.id}/registered-patch-days`)
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
      cancel(event) {
        this.$http.delete(`/projects/${this.project.id}/cancel`, {
          data: {
            patch_day_id: this.patch_day.id
          }
        })
          .then(response => {
            eventBus.$emit('info.snackbar',
              `Successfully cancelled ${this.patch_day.name}!`)
            eventBus.$emit('patch_day.cancelled')

            this.isOpen = false
          })
          .catch(error => {
            console.error(error)
            eventBus.$emit('info.snackbar', error.response.data.error)
          })
      }
    }
  }
</script>