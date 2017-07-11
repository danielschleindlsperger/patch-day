<template>
    <v-dialog v-model="isOpen" :width="500">
        <v-card>
            <v-card-title class="pa-4">
                <h2 class="title ma-0">Delete {{ user.name }} ?</h2>
            </v-card-title>
            <v-card-text>
                Deleting {{ user.name }} may have sideeffects and the
                deletion may not be reversible.
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
    name: 'delete-user',
    data() {
      return {
        isOpen: false,
        user: {}
      }
    },
    mounted () {
      eventBus.$on('user.delete.modal', user => {
        this.user = user
        this.isOpen = true
      })
    },
    methods: {
      deleteProject() {
        this.isOpen = false
        this.$http.delete(`/users/${this.user.id}`)
          .then(response => {
            if (response.status === 200) {
              eventBus.$emit('user.deleted', this.user)
              eventBus.$emit('info.snackbar',
                `User ${this.user.name} deleted successfully!`)
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