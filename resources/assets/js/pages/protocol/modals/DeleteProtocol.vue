<template>
    <v-dialog v-model="isOpen" width="640">
        <v-card>

            <v-card-title class="pa-4">
                <h2 class="title ma-0">
                    Delete {{ protocol.patch_day.name }}?
                </h2>
            </v-card-title>

            <v-card-text>
                Deleting a protocol may have sideeffects and the deletion may not be reversible.
            </v-card-text>

            <v-card-actions>
                <v-spacer></v-spacer>

                <v-btn class="green--text darken-1" flat="flat"
                       @click.native="isOpen = false">Cancel
                </v-btn>

                <v-btn error flat="flat"
                       @click.native="deleteProtocol">Delete
                </v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<script>
  import eventBus from 'components/event-bus'

  export default {
    name: 'delete-protocol',
    data() {
      return {
        isOpen: false,
        protocol: {
          protocol_number: null,
          patch_day: {
            name: ''
          }
        }
      }
    },
    mounted () {
      eventBus.$on('protocol.delete.modal', protocol => {
        this.protocol = protocol
        this.isOpen = true
      })
    },
    methods: {
      deleteProtocol() {
        this.isOpen = false
        this.$http.delete(`/protocols/${this.protocol.id}`)
          .then(response => {
            if (response.status === 200) {
              eventBus.$emit('protocol.deleted', this.protocol)
              eventBus.$emit('info.snackbar',
                `Protocol deleted successfully!`)
            }
          })
          .catch(error => {
            console.log(error.response.data)
            eventBus.$emit('info.snackbar', error.response.data)
          })
      }
    }
  }
</script>