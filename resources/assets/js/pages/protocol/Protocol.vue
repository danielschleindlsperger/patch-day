<template>
    <div>
        <v-container>
            <v-layout justify-center child-flex[-sm]>
                <h1 class="display-1 text-xs-center flex">
                    PatchDay #{{ patch_day.id }}
                </h1>

                <v-btn class="flex"
                       flat="flat" icon ripple
                       @click.native="editProtocolModal($event)">
                    <v-icon class="grey--text">
                        mode_edit
                    </v-icon>
                </v-btn>

                <v-btn class="flex"
                       flat="flat" icon ripple
                       @click.native="deleteProtocolModal($event)">
                    <v-icon class="grey--text">
                        delete
                    </v-icon>
                </v-btn>
            </v-layout>

            <h2 class="headline text-xs-center">
                Protocol for <span class="uppercase">{{ project.name }}</span>
            </h2>

            <div>
                Done:
                <v-icon>
                    {{ protocol.done | checkIcon }}
                </v-icon>
            </div>
            <div>
                Price: {{ protocol.price | currency('EUR', true) }}
            </div>
            <div>
                Date: {{ protocol.date | Date }}
            </div>
            <div v-if="protocol.comment">
                Comment: {{ protocol.comment }}
            </div>
        </v-container>
        <delete-protocol></delete-protocol>
        <edit-protocol></edit-protocol>
    </div>
</template>

<script>
  import eventBus from 'components/event-bus'
  import filters from 'mixins/filters'

  import DeleteProtocol from 'pages/protocol/DeleteProtocol'
  import EditProtocol from 'pages/protocol/EditProtocol'

  export default {
    components: {
      DeleteProtocol,
      EditProtocol,
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
        }
      }
    },
    methods: {
      checkOffModal(event) {
        event.preventDefault()
        event.stopPropagation()
        eventBus.$emit('protocol.checkoff.modal', this.protocol)
      },
      deleteProtocolModal(event) {
        event.preventDefault()
        event.stopPropagation()
        eventBus.$emit('protocol.delete.modal', this.protocol)
      },
      editProtocolModal(event) {
        event.preventDefault()
        event.stopPropagation()
        eventBus.$emit('protocol.edit.modal', this.protocol)
      },
      getProtocol() {
        const ID = this.$route.params.id
        this.$http.get(`/protocols/${ID}`)
          .then(response => {
            this.protocol = response.data
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
</style>