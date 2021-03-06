<template>
    <v-dialog v-model="isOpen" max-width="640" class="create-project-modal" persistent>
        <v-card>
            <v-card-title class="pa-4">
                <h2 class="title ma-0">Create Project</h2>
            </v-card-title>
            <v-card-text>
                <v-container fluid>
                    <v-text-field label="Name" required
                                  v-model="project.name"/>
                    <v-select
                            :items="companies"
                            item-text="name"
                            item-value="id"
                            v-model="project.company"
                            label="Associated company"
                            required auto
                            max-height="320"
                            light
                            :rules="rules.company"
                    />
                    <v-layout>
                        <v-flex xs12 md6 mr-4>
                            <v-text-field
                                    name="cost"
                                    label="Base price/PatchDay*"
                                    v-model.number="project.base_price"
                                    type="number"
                                    min="0"
                                    step="0.01"
                                    prepend-icon="euro_symbol"
                            ></v-text-field>
                        </v-flex>
                        <v-flex xs12 md6 ml-4>
                            <v-text-field
                                    name="cost"
                                    label="Penalty for missed PatchDays*"
                                    v-model.number="project.penalty"
                                    type="number"
                                    min="0"
                                    step="0.01"
                                    prepend-icon="euro_symbol"
                            >
                            </v-text-field>
                        </v-flex>
                    </v-layout>

                    <div>
                        <small>Technology not in list?</small>
                        <v-btn @click.native="createTechnologyModal($event)">
                            Create new Tech
                        </v-btn>
                    </div>

                    <v-select
                            label="Installed software"
                            :items="technologies"
                            v-model="defaultTech"
                            item-value="id"
                            item-text="canonical_name"
                            multiple
                            chips
                            light
                            max-height="500"
                            autocomplete
                            hint="Pick the default software versions."
                            persistent-hint
                    >
                    </v-select>
                    <small>*indicates required field</small>
                </v-container>
            </v-card-text>
            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn class="green--text darken-1" flat="flat"
                       @click.prevent.stop="closeModal">
                    Close
                </v-btn>
                <v-btn class="green--text darken-1" flat="flat"
                       @click.prevent.stop="createProject()">
                    Save
                </v-btn>
            </v-card-actions>
        </v-card>
        <create-technology-modal :event="'project.create.modal'"></create-technology-modal>
    </v-dialog>
</template>

<script>
  import eventBus from 'components/event-bus'
  import repo from 'repository'
  import CreateTechnologyModal from 'components/modals/CreateTechnologyModal'

  export default {
    name: 'create-project',
    components: {
      CreateTechnologyModal,
    },
    data() {
      return this.defaultState()
    },
    mounted () {
      eventBus.$on('project.create.modal', () => {
        this.getCompanies()
        this.getTechnologies()
        this.isOpen = true
      })
    },
    methods: {
      createProject() {
        this.project.company_id = this.project.company

        const payload = {
          name: this.project.name,
          company_id: this.project.company,
          base_price: this.project.base_price,
          penalty: this.project.penalty,
          technologies: this.defaultTech
        }

        repo.project.create(payload).then(() => {
          this.isOpen = false
          this.project.name = ''
          this.project.company = {}
          this.defaultTech = []
        })
        this.project.company_id = this.project.company
      },
      getCompanies() {
        repo.company.getAll().then((companies) => {
          this.companies = companies
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
      },
      defaultState() {
        return {
          isOpen: false,
          project: {
            name: '',
            company: null,
            base_price: 299.99,
            penalty: 99.99,
          },
          companies: [],
          rules: {
            company: [
              () => {
                if (!this.project.company) {
                  return true
                }
                return Number.isInteger(this.project.company)
              },
            ]
          },
          technologies: [],
          defaultTech: [],
        }
      },
      closeModal() {
        Object.assign(this.$data, this.defaultState())
      }
    }
  }
</script>

<style lang="scss">
    .create-project-modal .dialog {
        overflow-y: visible;
    }
</style>