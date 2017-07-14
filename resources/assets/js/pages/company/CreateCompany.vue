<template>
    <v-dialog v-model="isOpen">
        <v-card>
            <v-card-title class="pa-4">
                <h2 class="title ma-0">Create company</h2>
            </v-card-title>
            <v-card-text>
                <v-container fluid>
                    <v-text-field label="Name" required
                                  v-model="company.name"/>
                    <small>*indicates required field</small>
                </v-container>
            </v-card-text>
            <v-card-actions justify-end>
                <v-spacer></v-spacer>
                <v-btn class="green--text darken-1" flat="flat"
                       @click.native="isOpen = false">Close
                </v-btn>
                <v-btn class="green--text darken-1" flat="flat"
                       @click.native="createCompany()">Save
                </v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<script>
  import eventBus from 'components/event-bus'
  import repo from 'repository'

  export default {
    name: 'create-company',
    data() {
      return {
        isOpen: false,
        company: {
          name: '',
        }
      }
    },
    mounted () {
      eventBus.$on('company.create.modal', () => {
        this.isOpen = true
      })
    },
    methods: {
      createCompany() {
        repo.company.create(this.company.id, this.company).then(() => {
          this.isOpen = false
          this.company.name = ''
        })
      }
    }
  }
</script>