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
                                light required auto
                                max-height="320"
                                :rules="rules.company"
                        />
                        <v-row>
                            <v-col xs12 md6>
                                <v-text-field
                                        name="cost"
                                        label="Price/PatchDay in Cents*"
                                        v-model="project.patch_day.cost"
                                        type="number"
                                ></v-text-field>
                            </v-col>
                            <v-col xs12 md6>
                                <v-switch primary
                                          success
                                          hide-details
                                          label="Active*"
                                          v-model="project.patch_day.active"/>
                            </v-col>
                        </v-row>
                        <v-subheader>
                            Every {{ project.patch_day.interval
                            }} months.* (Between 1 and 12)
                        </v-subheader>
                        <v-slider v-model="project.patch_day.interval"
                                  :min="1" :max="12" :step="1" light/>
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
          company: {},
          patch_day: {
            cost: 20000,
            active: false,
            interval: 2,
          }
        },
        companies: [],
        rules: {
          company: [
            () => {
              return this.project.company &&
                Number.isInteger(this.project.company.id)
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
        this.project.company_id = this.project.company.id

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