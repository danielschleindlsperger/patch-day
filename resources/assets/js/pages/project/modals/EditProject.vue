<template>
    <v-dialog persistent v-model="isOpen" width="640"
              class="create-project-modal">
        <v-card>
            <v-card-title class="pa-4">
                <h2 class="title ma-0">Edit Project</h2>
            </v-card-title>
            <v-card-text>
                <v-container fluid>
                    <v-text-field label="Name" required
                                  v-model="project.name"/>
                    <v-select
                            :items="companies"
                            item-text="name"
                            item-value="id"
                            v-model="project.company_id"
                            label="Associated company"
                            light required auto
                            max-height="320"
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
                       @click.native="editProject()">Save
                </v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<script>
  import eventBus from 'components/event-bus'
  import repo from 'repository'

  export default {
    name: 'edit-project',
    data() {
      return {
        isOpen: false,
        project: {
          name: '',
          company_id: null,
          company: {},
        },
        companies: [],
        rules: {
          company: [
            () => {
              return this.project.company_id &&
                Number.isInteger(this.project.company_id)
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

      eventBus.$on('project.edit.modal', project => {
        this.project = JSON.parse(JSON.stringify(project))
        this.isOpen = true
      })
    },
    methods: {
      editProject() {
        const payload = {
          name: this.project.name,
          base_price: this.project.base_price,
          penalty: this.project.penalty,
          company_id: this.project.company_id,
        }

        repo.project.edit(this.project.id, payload).then(() => {
          this.isOpen = false
        })
      },
    },
  }
</script>

<style lang="scss">
    .create-project-modal .dialog {
        overflow-y: visible;
    }
</style>