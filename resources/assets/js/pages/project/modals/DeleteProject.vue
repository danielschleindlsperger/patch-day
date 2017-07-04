<template>
    <v-dialog v-model="isOpen">
        <v-card>
            <v-card-row>
                <v-card-title>Delete {{ project.name }} ?</v-card-title>
            </v-card-row>
            <v-card-row>
                <v-card-text>
                    Deleting {{ project.name
                    }} may have sideeffects and the deletion may not be reversible.
                </v-card-text>
            </v-card-row>
            <v-card-row actions>
                <v-btn class="green--text darken-1" flat="flat"
                       @click.native="isOpen = false">PLs no
                </v-btn>
                <v-btn error flat="flat"
                       @click.native="deleteProject">Delete
                </v-btn>
            </v-card-row>
        </v-card>
    </v-dialog>
</template>

<script>
  import eventBus from 'components/event-bus'
  export default {
    name: 'delete-project',
    data() {
      return {
        isOpen: false,
        project: {}
      }
    },
    mounted () {
      eventBus.$on('project.delete.modal', project => {
        this.project = project
        this.isOpen = true
      })
    },
    methods: {
      deleteProject() {
        this.isOpen = false
        this.$http.delete(`/projects/${this.project.id}`)
          .then(response => {
            console.log(this.project)
            if (response.status === 200) {
              eventBus.$emit('project.deleted', this.project)
              eventBus.$emit('info.snackbar',
                `${this.project.name} deleted successfully!`)
            }
          })
          .catch(error => {
            console.error(error)
            eventBus.$emit('info.snackbar', error.response.data)
          })
      }
    }
  }
</script>