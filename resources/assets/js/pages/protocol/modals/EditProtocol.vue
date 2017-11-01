<template>
    <div>
        <v-dialog v-model="isOpen" max-width="640" persistent>
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

                        <div>
                            <small>Technology not in list?</small>
                            <v-btn @click.native="createTechnologyModal($event)">
                                Create new Tech
                            </v-btn>
                        </div>

                        <delete-tech-from-project :project="protocol.project">
                        </delete-tech-from-project>

                        <v-select
                                label="Software updates"
                                v-bind:items="technologies"
                                v-model="upgraded_techs"
                                item-value="id"
                                item-text="canonical_name"
                                multiple
                                chips
                                light
                                max-height="500"
                                autocomplete
                                hint="Pick the updated software versions."
                                persistent-hint
                        >
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
        <create-technology-modal
                :event="'protocol.edit.modal'"></create-technology-modal>
    </div>
</template>

<script>
  import eventBus from 'components/event-bus'
  import repo from 'repository'
  import CreateTechnologyModal from 'components/modals/CreateTechnologyModal'
  import DeleteTechFromProject from 'components/modals/DeleteTechFromProject'

  export default {
    name: 'edit-protocol',
    components: {
      CreateTechnologyModal,
      DeleteTechFromProject,
    },
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
      eventBus.$on('protocol.edit.modal', (protocol) => {
        this.getTechnologies()
        if (protocol) {
          this.protocol = Object.assign({}, protocol)

          protocol.technology_updates.forEach(tech => {
            this.upgraded_techs.push(tech.id)
          })
        }

        this.isOpen = true
      })
    },
    methods: {
      editProtocol() {
        const payload = {
          comment: this.protocol.comment,
          done: this.protocol.done,
          technology_updates: this.upgraded_techs,
        }

        repo.protocol.edit(this.protocol.id, payload).then(() => {
          this.isOpen = false
        })
      },
      getTechnologies() {
        repo.technology.getAll().then((technologies) => {
          this.technologies = technologies
        })
      },
      createTechnologyModal(event) {
        event.preventDefault()
        event.stopPropagation()
        this.isOpen = false
        eventBus.$emit('technology.create.modal')
      }
    }
  }
</script>

<style lang="scss">
    .dialog--active {
        overflow-y: visible;
    }
</style>