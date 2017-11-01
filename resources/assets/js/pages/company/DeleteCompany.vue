<template>
    <v-dialog v-model="isOpen" max-width="640">
        <v-card>
            <v-card-title class="pa-4">
                <h2 class="title ma-0">Delete {{ company.name }} ?</h2>
            </v-card-title>
            <v-card-text>
                Deleting {{ company.name }} may have side effects and the deletion may not be reversible.
            </v-card-text>
            <v-card-actions>
                <v-spacer />
                <v-btn class="green--text darken-1" flat="flat"
                       @click.native="isOpen = false">Cancel
                </v-btn>
                <v-btn color="error" flat="flat"
                       @click.stop="deleteCompany">Delete
                </v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<script>
  import eventBus from 'components/event-bus'
  import repo from 'repository'

  export default {
    name: 'delete-company',
    data() {
      return {
        isOpen: false,
        company: {}
      }
    },
    mounted () {
      eventBus.$on('company.delete.modal', company => {
        this.company = company
        this.isOpen = true
      })
    },
    methods: {
      deleteCompany() {
        this.isOpen = false
        repo.company.delete(this.company.id)
      }
    }
  }
</script>