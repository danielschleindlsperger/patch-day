<template>
    <v-dialog v-model="isOpen">
        <v-card>
            <v-card-title class="pa-4">
                <h2 class="title ma-0">Edit Company</h2>
            </v-card-title>
            <v-card-text>
                <v-container fluid>
                    <v-text-field label="Name" v-model="company.name"/>
                    <small>*indicates required field</small>
                </v-container>
            </v-card-text>
            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn class="green--text darken-1" flat="flat"
                       @click.native="isOpen = false">Close
                </v-btn>
                <v-btn class="green--text darken-1" flat="flat"
                       @click.native="editCompany()">Save
                </v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<script>
  import eventBus from 'components/event-bus'
  import repo from 'repository'

  export default {
    name: 'edit-company',
    data() {
      return {
        isOpen: false,
        company: {}
      }
    },
    mounted () {
      eventBus.$on('company.edit.modal', (company) => {
        this.company = company
        this.isOpen = true
      })
    },
    methods: {
      editCompany() {
        repo.company.edit(this.company.id, this.company).then(() => {
          this.isOpen = false
        })
      }
    }
  }
</script>