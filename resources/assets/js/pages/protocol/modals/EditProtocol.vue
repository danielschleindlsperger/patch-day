<template>
    <v-dialog v-model="isOpen" width="640" persistent>
        <v-card>

            <v-card-title class="pa-4">
                <h2 class="title ma-0">Edit Protocol (Check-off)</h2>
            </v-card-title>

            <v-card-text>
                <v-container fluid>
                    <v-text-field
                            name="comment"
                            label="Comment"
                            multi-line
                            v-model="protocol.comment"
                    ></v-text-field>

                    <v-select
                            label="Software updates"
                            v-bind:items="technologies"
                            v-model="upgraded_techs"
                            item-value="id"
                            multiple
                            chips
                            light
                            max-height="500"
                            autocomplete
                            hint="Pick the updated software versions."
                            persistent-hint
                    >
                        <template slot="selection" scope="data">
                            <v-chip
                                    close
                                    @input="data.parent.selectItem(data.item)"
                                    @click.native.stop
                                    class="chip--select-multi"
                                    :key="data.item"
                            >
                                {{ data.item.name }}
                                {{ data.item.version }}
                            </v-chip>
                        </template>

                        <template slot="item" scope="data">
                            <v-list-tile-content>
                                <v-list-tile-title
                                        v-html="data.item.name"></v-list-tile-title>
                                <v-list-tile-sub-title
                                        v-html="data.item.version"></v-list-tile-sub-title>
                            </v-list-tile-content>
                        </template>
                    </v-select>

                    <v-checkbox
                            label="Done"
                            primary
                            v-model="protocol.done"/>

                </v-container>
            </v-card-text>

            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn class="green--text darken-1" flat="flat"
                       @click.native="isOpen = false">Close
                </v-btn>
                <v-btn class="green--text darken-1" flat="flat"
                       @click.native="editProtocol()">Save
                </v-btn>
            </v-card-actions>
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
          date: null,
          done: false,
          patch_day: {
            id: null,
          },
          project: {
            name: '',
          }
        },
        technologies: [],
        upgraded_techs: [],
      }
    },
    mounted () {

      this.getTechnologies()

      eventBus.$on('protocol.edit.modal', protocol => {
        this.protocol = Object.assign({}, protocol)

        protocol.technology_updates.forEach(tech => {
          this.upgraded_techs.push(tech.id)
        })

        this.isOpen = true
      })
    },
    methods: {
      editProtocol() {
        this.$http.put(`/protocols/${this.protocol.id}`, {
          comment: this.protocol.comment,
          done: this.protocol.done,
          technology_updates: this.upgraded_techs,
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
      },
      getTechnologies() {
        this.$http.get(`/technologies`)
          .then(response => {
            this.technologies = response.data
          })
          .catch(error => {
            console.error(error)
            eventBus.$emit('info.snackbar', error.response.data.error)
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