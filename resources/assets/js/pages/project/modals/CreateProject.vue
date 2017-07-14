<template>
    <v-dialog v-model="isOpen" width="640" class="create-project-modal">
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
                                    v-model="project.base_price"
                                    type="number"
                                    min="0"
                                    suffix="Cents"
                            ></v-text-field>
                        </v-flex>
                        <v-flex xs12 md6 ml-4>
                            <v-text-field
                                    name="cost"
                                    label="Penalty for missed PatchDays*"
                                    v-model="project.penalty"
                                    type="number"
                                    min="0"
                                    suffix="Cents"
                            >
                            </v-text-field>
                        </v-flex>
                    </v-layout>
                    <small>*indicates required field</small>
                </v-container>
            </v-card-text>
            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn class="green--text darken-1" flat="flat"
                       @click.native="isOpen = false">Close
                </v-btn>
                <v-btn class="green--text darken-1" flat="flat"
                       @click.native="createProject()">Save
                </v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<script>
  import eventBus from 'components/event-bus'
  import repo from 'repository'

  export default {
    name: 'create-project',
    data() {
      return {
        isOpen: false,
        project: {
          name: '',
          company: null,
          base_price: 20000,
          penalty: 10000,
        },
        companies: [],
        rules: {
          company: [
            () => {
              return this.project.company &&
                Number.isInteger(this.project.company)
                || 'Please select an entry'
            },
          ]
        }
      }
    },
    mounted () {
      repo.company.getAll().then((companies) => {
        this.companies = companies
      })

      eventBus.$on('project.create.modal', () => {
        this.isOpen = true
      })
    },
    methods: {
      createProject() {
        this.project.company_id = this.project.company

        repo.project.create(this.project).then(() => {
          this.isOpen = false
          this.project.name = ''
          this.project.company = {}
        })
        this.project.company_id = this.project.company
      },
    }
  }
</script>

<style lang="scss">
    .create-project-modal .dialog {
        overflow-y: visible;
    }
</style>