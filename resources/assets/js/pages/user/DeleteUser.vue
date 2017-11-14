<template>
    <v-dialog v-model="isOpen" max-width="640">
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
                <v-btn color="error" flat
                       @click.native="deleteUser">Delete
                </v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<script>
  import eventBus from 'components/event-bus'
  import repo from 'repository'

  export default {
    name: 'delete-user',
    data() {
      return {
        isOpen: false,
        user: {}
      }
    },
    mounted () {
      eventBus.$on('user.delete.modal', (user) => {
        this.user = user
        this.isOpen = true
      })
    },
    methods: {
      deleteUser() {
        this.isOpen = false
        repo.user.delete(this.user.id)
      }
    }
  }
</script>