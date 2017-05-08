<template>
    <v-dialog v-model="isOpen" width="640">
        <v-card>
            <v-card-row>
                <v-card-title>Edit PatchDay
                    #{{protocol.protocol_number}}
                </v-card-title>
            </v-card-row>
            <v-card-row>
                <v-card-text>
                    <v-container fluid>
                        <v-text-field
                                name="comment"
                                label="Comment"
                                multi-line
                                v-model="protocol.comment"
                        ></v-text-field>
                        <v-checkbox
                                label="Done"
                                primary
                                v-model="protocol.done"/>
                        <v-menu
                                lazy
                                :close-on-content-click="true"
                                v-model="datePickerOpen"
                                transition="v-scale-transition"
                                offset-y
                                :nudge-left="56"
                        >
                            <v-text-field
                                    slot="activator"
                                    label="Date"
                                    v-model="protocol.due_date"
                                    prepend-icon="event"
                                    readonly
                            ></v-text-field>
                            <v-date-picker v-model="protocol.due_date" no-title
                                           scrollable>
                            </v-date-picker>
                        </v-menu>
                    </v-container>
                </v-card-text>
            </v-card-row>
            <v-card-row actions>
                <v-btn class="green--text darken-1" flat="flat"
                       @click.native="isOpen = false">Close
                </v-btn>
                <v-btn class="green--text darken-1" flat="flat"
                       @click.native="editProtocol()">Save
                </v-btn>
            </v-card-row>
        </v-card>
    </v-dialog>
</template>

<script>
  import eventBus from 'components/event-bus'
  export default {
    name: 'edit-protocol',
    data() {
      return {
        isOpen: false,
        datePickerOpen: false,
        protocol: {
          protocol_number: null,
          comment: '',
          due_date: null,
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
    mounted () {
      eventBus.$on('protocol.edit.modal', protocol => {
        this.protocol = Object.assign({}, protocol)
        this.isOpen = true
      })
    },
    methods: {
      editProtocol() {
        this.$http.put(`/protocols/${this.protocol.id}`, {
          comment: this.protocol.comment,
          done: this.protocol.done,
          due_date: this.protocol.due_date,
        })
          .then(response => {
            if (response.status === 200) {
              eventBus.$emit('protocol.edited', this.protocol)
              eventBus.$emit('info.snackbar', `Protocol edited successfully!`)
              this.isOpen = false
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

<style lang="scss">
    .dialog--active {
        overflow-y: visible;
    }
</style>