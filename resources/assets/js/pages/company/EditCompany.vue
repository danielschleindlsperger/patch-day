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
  export default {
    name: 'edit-company',
    props: ['company'],
    data() {
      return {
        isOpen: false,
      }
    },
    mounted () {
      eventBus.$on('company.edit.modal', () => {
        this.isOpen = true
      })
    },
    methods: {
      editCompany() {
        this.$http.put(`/companies/${this.company.id}`, {
          name: this.company.name
        })
          .then(response => {
            if (response.status === 200) {
              eventBus.$emit('company.edited')
              eventBus.$emit('info.snackbar',
                `${this.company.name} edited successfully!`)
              this.isOpen = false
            }
          })
          .catch(error => {
            console.log(error.response.data)
            eventBus.$emit('info.snackbar', error.response.data)
          })
      }
    }
  }
</script>