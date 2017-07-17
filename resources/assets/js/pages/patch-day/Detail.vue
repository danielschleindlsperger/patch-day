<template>
    <div>
        <v-container>
            <h1 class="display-1 text-xs-center">{{ patch_day.name }}</h1>

            <h2 class="headline text-xs-center">
                {{ patch_day.date | Date }}
            </h2>

            <h2 class="headline text-xs-center">
                Status: {{ patch_day.status }}
            </h2>

            <div v-if="patch_day.protocols.length > 0">
                <v-subheader>Signed up</v-subheader>
                <v-card>
                    <v-list>
                        <v-list-tile v-for="protocol in patch_day.protocols"
                                     :key="protocol.id"
                                     :to="`/protocols/${protocol.id}`">
                            <v-list-tile-content>
                                <v-list-tile-title>
                                    {{ protocol.project.company.name }}
                                    /
                                    <strong>{{ protocol.project.name }}</strong>
                                </v-list-tile-title>
                            </v-list-tile-content>
                        </v-list-tile>
                    </v-list>
                </v-card>
            </div>
            <div v-else>
                Nobody signed up yet.
            </div>
        </v-container>

        <fab :patch_day="patch_day" :fabActions="fabActions"
             :show="showFab"></fab>
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
            icon: 'delete',
            color: 'red',
            event: 'patch_day.delete.modal',
            tooltip: 'Delete PatchDay',
          },
          {
            icon: 'add',
            color: 'indigo',
            event: 'patch_day.create.modal',
            tooltip:  'Create PatchDay',
          },
          {
            icon: 'edit',
            color: 'green',
            event: 'patch_day.edit.modal',
            tooltip:'Edit PatchDay',
          },
          {
            icon: 'update',
            color: 'yellow',
            event: '',
            tooltip: 'Handle PatchDay',
          },
        ],
        showFab: false,
        patch_day: {
          id: null,
          date: '',
          protocols: []
        },
      }
    },
    beforeRouteEnter (to, from, next) {
      repo.patch_day.get(to.params.id)
        .then((data) => {
          next((vm) => {
            vm.patch_day = data
            vm.showFab = true
          })
        })
    },
    mounted() {

      eventBus.$on('patch_day.edited', () => {
        repo.patch_day.get(this.patch_day.id).then((patch_day) => {
          this.patch_day = patch_day
        })
      }).$on('patch_day.deleted', id => {
        // this protocol was deleted
        if (id === this.patch_day.id) {
          this.$router.push(`/patch-days`)
        }
      })
    },
  }
</script>