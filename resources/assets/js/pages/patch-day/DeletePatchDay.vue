<template>
    <v-dialog v-model="isOpen">
        <v-card>
            <v-card-row>
                <v-card-title>Delete PatchDay?</v-card-title>
            </v-card-row>
            <v-card-row>
                <v-card-text>
                    Deleting PatchDay #{{ patch_day.id }} on
                    {{ patch_day.date | Date }} may have sideeffects and the
                    deletion may not be reversible.
                </v-card-text>
            </v-card-row>
            <v-card-row actions>
                <v-btn class="green--text darken-1" flat="flat"
                       @click.native="isOpen = false">Don't Delete
                </v-btn>
                <v-btn error flat="flat"
                       @click.native="deletePatchDay">Delete
                </v-btn>
            </v-card-row>
        </v-card>
    </v-dialog>
</template>

<script>
  import eventBus from 'components/event-bus'
  import filters from 'mixins/filters'

  export default {
    name: 'delete-patch-day',
    mixins: [filters],
    data() {
      return {
        isOpen: false,
        patch_day: {}
      }
    },
    mounted () {
      eventBus.$on('patch_day.delete.modal', patch_day => {
        this.patch_day = patch_day
        this.isOpen = true
      })
    },
    methods: {
      deletePatchDay() {
        this.isOpen = false
        this.$http.delete(`/patch-days/${this.patch_day.id}`)
          .then(response => {
            if (response.status === 200) {
              eventBus.$emit('patch_day.deleted', this.patch_day)
              eventBus.$emit('info.snackbar',
                `PatchDay deleted successfully!`)
            }
          })
          .catch(error => {
            console.error(error)
            eventBus.$emit('info.snackbar', error.response.data)
          })
      }
    }
  }
</script>