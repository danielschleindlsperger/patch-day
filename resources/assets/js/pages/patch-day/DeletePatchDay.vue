<template>
    <v-dialog v-model="isOpen">
        <v-card>

            <v-card-title class="pa-4">
                <h2 class="title ma-0">Delete PatchDay?</h2>
            </v-card-title>

            <v-card-text>
                Deleting PatchDay #{{ patch_day.id }} on
                {{ patch_day.date | Date }} may have sideeffects and the
                deletion may not be reversible.
            </v-card-text>

            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn class="green--text darken-1" flat="flat"
                       @click.native="isOpen = false">Cancel
                </v-btn>
                <v-btn error flat="flat"
                       @click.native="deletePatchDay">Delete
                </v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<script>
  import eventBus from 'components/event-bus'
  import filters from 'mixins/filters'
  import repo from 'repository'

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
        repo.patch_day.delete(this.patch_day.id)
      }
    }
  }
</script>