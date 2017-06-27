<template>
    <v-dialog v-model="isOpen" width="640" class="create-project-modal">
        <v-card>
            <v-card-row>
                <v-card-title>Create Project</v-card-title>
            </v-card-row>
            <v-card-row>
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
                                dark
                                :rules="rules.company"
                        />
                        <v-layout>
                            <v-flex xs12 md6>
                                <v-text-field
                                        name="cost"
                                        label="Base price/PatchDay*"
                                        v-model="project.base_price"
                                        type="number"
                                        min="0"
                                        suffix="Cents"
                                ></v-text-field>
                            </v-flex>
                            <v-flex xs12 md6>
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
            </v-card-row>
            <v-card-row actions>
                <v-btn class="green--text darken-1" flat="flat"
                       @click.native="isOpen = false">Close
                </v-btn>
                <v-btn class="green--text darken-1" flat="flat"
                       @click.native="createProject()">Save
                </v-btn>
            </v-card-row>
        </v-card>
    </v-dialog>
</template>

<script>
  import eventBus from 'components/event-bus'
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
      this.getCompanies()

      eventBus.$on('project.create.modal', () => {
        this.isOpen = true
      })
    },
    methods: {
      createProject() {
        this.project.company_id = this.project.company

        this.$http.post('/projects', this.project)
          .then(response => {
            if (response.status === 200) {
              eventBus.$emit('project.created')
              eventBus.$emit('info.snackbar',
                `${this.project.name} created successfully!`)
              this.isOpen = false
              this.project.name = ''
              this.project.company = {}
            }
          })
          .catch(error => {
            console.error(error)
            eventBus.$emit('info.snackbar', error.response.data)
          })
      },
      getCompanies() {
        this.$http.get('/companies')
          .then(response => {
            this.companies = response.data
          })
          .catch(error => {
            console.error(error)
          })
      },
    }
  }
</script>

<style lang="scss">
    .create-project-modal .dialog {
        overflow-y: visible;
    }
</style>