<template>
    <div>
        <v-container>
            <h1 class="display-1 text-xs-center flex">
                {{ patch_day.name }}
            </h1>

            <h2 class="headline text-xs-center">
                Protocol for
                <router-link class="uppercase"
                             :to="`/projects/${project.id}`">
                    {{ project.name }}
                </router-link>
            </h2>

            <div class="info-item">
                Done:
                <v-icon>
                    {{ protocol.done | checkIcon }}
                </v-icon>
            </div>
            <div class="info-item">
                Price: {{ protocol.price | currency('EUR', true) }}
            </div>
            <div class="info-item">
                Date: {{ protocol.date | Date }}
            </div>
            <div v-if="protocol.comment" class="info-item">
                <h3 class="subheading mb-0">Comment:</h3>
                <div class="body-2">
                    {{ protocol.comment }}
                </div>
            </div>
            <div class="info-item">
                <h3 class="subheading mb-0">Version updates:</h3>
                <div v-for="update in protocol.technology_updates">
                    <span>{{ update.name }}</span>
                    <v-icon>trending_flat</v-icon>
                    <span>{{ update.version }}</span>
                </div>
            </div>
        </v-container>
        <fab :protocol="protocol" :fabActions="fabActions"></fab>
    </div>
</template>

<script>
  import eventBus from 'components/event-bus'
  import filters from 'mixins/filters'

  import Fab from 'pages/protocol/Fab'

  export default {
    components: {
      Fab,
    },
    mixins: [filters],
    data() {
      return {
        protocol: {
          done: false,
          comment: '',
          date: '',
          patch_day: {
            id: null,
          },
          project: {
            name: '',
            current_technologies: [],
            technology_history: [],
          }
        },
        fabActions: [
          {
            icon: 'delete',
            color: 'red',
            event: 'protocol.delete.modal'
          },
          {
            icon: 'edit',
            color: 'green',
            event: 'protocol.edit.modal',
          },
        ],
      }
    },
    methods: {
      getProtocol() {
        const ID = this.$route.params.id
        this.$http.get(`/protocols/${ID}`)
          .then(response => {
            this.protocol = response.data
            eventBus.$emit('page.loading', false)
          })
          .catch(error => {
            console.error(error)
            eventBus.$emit('info.snackbar', error.response.data.error)
            if (error.response.status === 404) {
              this.$router.push({name: 'not-found'})
            }
          })
      }
    },
    computed: {
      patch_day() {
        return this.protocol.patch_day
      },
      project() {
        return this.protocol.project
      },
    },
    mounted() {
      this.getProtocol()

      eventBus.$on('protocol.edited', protocol => {
        this.protocol = Object.assign({}, protocol)
        this.getProtocol()
      })

      eventBus.$on('protocol.deleted', protocol => {
        // this protocol was deleted
        if (protocol.id === this.protocol.id) {
          this.$router.push(`/projects/${this.protocol.project.id}`)
        }
      })
    }
  }
</script>

<style lang="scss" scoped>
    .uppercase {
        text-transform: uppercase;
    }
    .info-item {
        margin-bottom: 1em;
    }
</style>