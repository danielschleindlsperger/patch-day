<template>
    <div>
        <v-container>
            <h1 class="display-2 text-xs-center">Patch Days</h1>
            <div class="button-row">
                <v-btn primary dark
                       @click.native="openCreatePatchDayModal($event)">
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
                                PatchDay #{{ patch_day.id }}
                            </v-list-tile-title>
                            <v-list-tile-sub-title>
                                {{ patch_day.date | Date }}
                            </v-list-tile-sub-title>
                        </v-list-tile-content>
                        <v-list-tile-action>
                            <v-btn icon ripple
                                   @click.native="deleteItem($event, project)">
                                <v-icon class="grey--text">
                                    delete
                                </v-icon>
                            </v-btn>
                        </v-list-tile-action>
                    </v-list-tile>
                </v-list-item>
            </v-list>
        </v-container>

        <delete-project></delete-project>
        <create-patch-day></create-patch-day>
    </div>
</template>

<script>
  import eventBus from 'components/event-bus'
  import filters from 'mixins/filters'

  import DeleteProject from 'pages/project/DeleteProject'
  import CreatePatchDay from 'pages/patch-day/CreatePatchDay'

  export default {
    components: {
      DeleteProject,
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
    },
    methods: {
      getPatchDays() {
        this.$http.get('/patch-days')
          .then(response => {
            this.patch_days = response.data
          })
          .catch(error => {
            error.response.data
          })
      },
      openCreatePatchDayModal(event) {
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