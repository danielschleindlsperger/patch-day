<template>
    <div>
        <v-container>
            <h1 class="display-2 text-xs-center">Patch Days</h1>

            <v-card v-if="patch_days.length">
                <v-list two-line>
                    <v-list-tile v-for="patch_day in patch_days"
                                 :key="patch_day.id"
                                 :to="`/patch-days/${patch_day.id}`">
                        <v-list-tile-content
                                :class="{ faded: patch_day.status === 'done' }">
                            <v-list-tile-title>
                                {{ patch_day.name }}
                            </v-list-tile-title>
                            <v-list-tile-sub-title>
                                {{ patch_day.date | Date }} -
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
                </v-list>
            </v-card>
        </v-container>

        <fab :fabActions="fabActions" :show="showFab"></fab>
    </div>
</template>

<script>
  import eventBus from 'components/event-bus'
  import filters from 'mixins/filters'
  import repo from 'repository'

  import Fab from 'pages/patch-day/Fab'

  export default {
    components: {
      Fab,
    },
    mixins: [filters],
    data() {
      return {
        fabActions: [
          {
            icon: 'add',
            color: 'indigo',
            event: 'patch_day.create.modal',
            tooltip: 'Create PatchDay',
          },
        ],
        showFab: false,
        patch_days: [],
      }
    },
    beforeRouteEnter (to, from, next) {
      repo.patch_day.getAll()
        .then((data) => {
          next((vm) => {
            vm.patch_days = data
            vm.showFab = true
          })
        })
    },
    mounted() {
      eventBus.$on('patch_day.created', () => {
        this.getPatchDays()
      }).$on('patch_day.deleted', () => {
        this.getPatchDays()
      })
    },
    methods: {
      getPatchDays() {
        repo.patch_day.getAll().then((patch_days) => {
          this.patch_days = patch_days
        })
      },
      deletePatchDayModal(event, patch_day) {
        event.preventDefault()
        event.stopPropagation()
        eventBus.$emit('patch_day.delete.modal', patch_day)
      },
    }
  }
</script>

<style lang="scss" scoped>
    .faded {
        opacity: .5;
    }
    .button-row {
        display: flex;
        flex-flow: row nowrap;
        justify-content: flex-end;
    }
</style>