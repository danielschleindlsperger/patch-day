<template>
    <v-dialog v-model="isOpen">
        <v-card>
            <v-card-title class="pa-4">
                <h2 class="title ma-0">Delete {{ project.name }} ?</h2>
            </v-card-title>
            <v-card-text>
                Deleting {{ project.name
                }} may have sideeffects and the deletion may not be reversible.
            </v-card-text>
            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn class="green--text darken-1" flat="flat"
                       @click.native="isOpen = false">Cancel
                </v-btn>
                <v-btn error flat="flat"
                       @click.native="deleteProject">Delete
                </v-btn>
            </v-card-actions>
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