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
                        <small>*indicates required field</small>
                    </v-container>
                </v-card-text>
            </v-card-row>
            <v-card-row actions>
                <v-btn class="green--text darken-1" flat="flat"
                       @click.native="isOpen = false">Close
                </v-btn>
                <v-btn class="green--text darken-1" flat="flat"
                       @click.native="editProject()">Save
                </v-btn>
            </v-card-row>
        </v-card>
    </v-dialog>
</template>

<script>
  import eventBus from 'components/event-bus'
  export default {
    name: 'edit-project',
    data() {
      return {
        isOpen: false,
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

      eventBus.$on('project.edit.modal', project => {
        this.project = Object.assign({}, project)
        this.isOpen = true
      })
    },
    methods: {
      editProject() {
        this.$http.put(`/projects/${this.project.id}`, {
          name: this.project.name,
          company_id: this.project.company.id,
        })
          .then(response => {
            if (response.status === 200) {
              eventBus.$emit('project.edited')
              eventBus.$emit('info.snackbar',
                `${this.project.name} edited successfully!`)
              this.isOpen = false
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