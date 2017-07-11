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

        <fab :patch_day="patch_day" :fabActions="fabActions"></fab>
    </div>
</template>

<script>
  import eventBus from 'components/event-bus'
  import filters from 'mixins/filters'

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
            event: 'patch_day.delete.modal'
          },
          {
            icon: 'add',
            color: 'indigo',
            event: 'patch_day.create.modal',
          },
          {
            icon: 'edit',
            color: 'green',
            event: 'patch_day.edit.modal',
          },
        ],
        patch_day: {
          id: null,
          date: '',
          protocols: []
        },
      }
    },
    mounted() {
      this.getPatchDay()

      eventBus.$on('patch_day.edited', () => {
        this.getPatchDay()
      })

      eventBus.$on('patch_day.deleted', patch_day => {
        // this protocol was deleted
        if (patch_day.id === this.patch_day.id) {
          this.$router.push(`/patch-days`)
        }
      })
    },
    methods: {
      getPatchDay() {
        const ID = this.$route.params.id
        this.$http.get(`/patch-days/${ID}`)
          .then(response => {
            this.patch_day = response.data
            eventBus.$emit('page.loading', false)
          })
          .catch(error => {
            console.error(error.response.data)
            if (error.response.status === 404) {
              this.$router.push({name: 'not-found'})
            }
          })
      },
    }
  }
</script>