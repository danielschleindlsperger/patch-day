<template>
    <v-dialog v-model="isOpen" persistent width="640"
              class="check-off-protocol-modal">
        <v-card>
            <v-card-row>
                <v-card-title>Check off PatchDay</v-card-title>
            </v-card-row>
            <v-card-row>
                <v-card-text>
                    <v-container fluid>
                        <v-text-field
                                name="comment"
                                label="Comment"
                                multi-line
                                v-model="checkOffProps.comment"
                        ></v-text-field>
                    </v-container>
                </v-card-text>
            </v-card-row>
            <v-card-row actions>
                <v-btn class="green--text darken-1" flat="flat"
                       @click.native="isOpen = false">Close
                </v-btn>
                <v-btn class="green--text darken-1" flat="flat"
                       @click.native="checkOff">Check-off
                </v-btn>
            </v-card-row>
        </v-card>
    </v-dialog>
</template>

<script>
  import eventBus from 'components/event-bus'
  export default {
    name: 'check-off-protocol',
    data() {
      return {
        isOpen: false,
        protocol: {
          protocol_number: null,
        },
        checkOffProps: {
          comment: '',
        },
      }
    },
    mounted () {
      eventBus.$on('protocol.checkoff.modal', (protocol) => {
        this.protocol = protocol
        this.isOpen = true
      })
    },
    methods: {
        checkOff() {
          this.$http.put(`/protocols/${this.protocol.id}`, {
            comment: this.checkOffProps.comment,
            done: true,
          })
            .then(response => {
              eventBus.$emit('protocol.checked-off')
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
  }
</script>

<style lang="scss">
    .check-off-protocol-modal .dialog {
        overflow-y: visible;
    }
</style>