<template>
    <div>
        <v-container>
            <h1 class="display-2 text-xs-center">Patch Days</h1>
            <div class="button-row">
                <v-btn primary dark
                       @click.native="createPatchDayModal($event)">
                    <v-icon class="white--text text--darken-2">
                        add_circle
                    </v-icon>
                </v-btn>
            </div>
            <v-list>
                <v-list-item v-for="patch_day in patch_days"
                             :key="patch_day.id">
                    <v-list-tile avatar router
                                 :href="`/patch-days/${patch_day.id}`">
                        <v-list-tile-content>
                            <v-list-tile-title>
                                {{ patch_day.name }}
                            </v-list-tile-title>
                            <v-list-tile-sub-title>
                                {{ patch_day.date | Date }}
                                {{ patch_day.status }}
                            </v-list-tile-sub-title>
                        </v-list-tile-content>
                        <v-list-tile-action>
                            <v-btn icon ripple
                                   @click.native="deletePatchDayModal($event,
                                    patch_day)">
                                <v-icon class="grey--text">
                                    delete
                                </v-icon>
                            </v-btn>
                        </v-list-tile-action>
                    </v-list-tile>
                </v-list-item>
            </v-list>
        </v-container>

        <delete-patch-day></delete-patch-day>
        <create-patch-day></create-patch-day>
    </div>
</template>

<script>
  import eventBus from 'components/event-bus'
  import filters from 'mixins/filters'

  import DeletePatchDay from 'pages/patch-day/DeletePatchDay'
  import CreatePatchDay from 'pages/patch-day/CreatePatchDay'

  export default {
    components: {
      DeletePatchDay,
      CreatePatchDay,
    },
    mixins: [filters],
    data() {
      return {
        patch_days: [],
      }
    },
    mounted() {
      this.getPatchDays()

      eventBus.$on('patch_day.created', () => {
        this.getPatchDays()
      })

      eventBus.$on('patch_day.deleted', () => {
        this.getPatchDays()
      })
    },
    methods: {
      getPatchDays() {
        this.$http.get('/patch-days')
          .then(response => {
            this.patch_days = response.data
            eventBus.$emit('page.loading', false)
          })
          .catch(error => {
            console.error(error.response.data)
            eventBus.$emit('info.snackbar', error.response.data.error)
          })
      },
      deletePatchDayModal(event, patch_day) {
        event.preventDefault()
        event.stopPropagation()
        eventBus.$emit('patch_day.delete.modal', patch_day)
      },
      createPatchDayModal(event) {
        event.preventDefault()
        event.stopPropagation()
        eventBus.$emit('patch_day.create.modal')
      }
    }
  }
</script>

<style lang="scss" scoped>
    .button-row {
        display: flex;
        flex-flow: row nowrap;
        justify-content: flex-end;
    }
</style>