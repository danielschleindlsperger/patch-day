<template>
    <div>
        <v-container>
            <h2 class="text-xs-center">
                {{ project.name }} - PatchDay #{{ protocol.protocol_number }}
            </h2>
            <div class="info-wrapper">
                <h3>{{ company.name }}</h3>
                <div class="button-row">
                    <v-btn flat="flat" icon ripple
                           @click.native="editProtocolModal($event)">
                        <v-icon class="grey--text">
                            mode_edit
                        </v-icon>
                    </v-btn>
                    <v-btn flat="flat" icon ripple
                           @click.native="deleteProtocolModal($event)">
                        <v-icon class="grey--text">
                            delete
                        </v-icon>
                    </v-btn>
                </div>
            </div>
            <div>
                Done:
                <v-icon>
                    {{ protocol.done | doneIcon }}
                </v-icon>
            </div>
            <div>
                Due: {{ protocol.due_date | Date }}
            </div>
            <div v-if="protocol.done">
                Comment: {{ protocol.comment }}
            </div>
            <div v-if="!protocol.done">
                <v-btn primary @click.native="checkOffModal($event)">
                    Check-off
                </v-btn>
                <check-off-protocol></check-off-protocol>
            </div>
        </v-container>
        <delete-protocol></delete-protocol>
        <edit-protocol></edit-protocol>
    </div>
</template>

<script>
  import eventBus from 'components/event-bus'
  import filters from 'mixins/filters'

  import CheckOffProtocol from 'pages/patch-day/CheckOffProtocol'
  import DeleteProtocol from 'pages/patch-day/DeleteProtocol'
  import EditProtocol from 'pages/patch-day/EditProtocol'

  export default {
    components: {
      CheckOffProtocol,
      DeleteProtocol,
      EditProtocol,
    },
    mixins: [filters],
    data() {
      return {
        protocol: {
          protocol_number: null,
          done: false,
          patch_day: {
            project: {
              name: '',
              company: {
                name: '',
              }
            }
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
        const protocolId = this.$route.params.id
        this.$http.get(`/protocols/${protocolId}`)
          .then(response => {
            this.protocol = response.data
            console.log(response.data)
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
      patchDay() {
        return this.protocol.patch_day
      },
      project() {
        return this.protocol.patch_day.project
      },
      company() {
        return this.protocol.patch_day.project.company
      },
    },
    mounted() {
      this.getProtocol()

      eventBus.$on('protocol.checked-off', () => {
        this.getProtocol()
      })

      eventBus.$on('protocol.edited', protocol => {
        this.protocol = Object.assign({}, protocol)
      })

      eventBus.$on('protocol.deleted', protocol => {
        // this protocol was deleted
        if (protocol.id === this.protocol.id) {
          this.$router.push(`/projects/${this.protocol.patch_day.project_id}`)
        }
      })
    }
  }
</script>

<style lang="scss" scoped>
    h1 {
        font-size: 48px;
        text-align: center;
    }

    .info-wrapper {
        display: flex;
        flex-flow: row nowrap;
        justify-content: space-between;
        h3 {
            font-size: 32px;
        }
        .button-row {
            display: flex;
            flex-flow: row nowrap;
            justify-content: flex-end;
        }
    }

    hr {
        margin: 1rem 0 3rem;
    }
</style>