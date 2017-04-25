<template>
    <v-dialog v-model="isOpen">
        <v-card>
            <v-card-row>
                <v-card-title>Create Company</v-card-title>
            </v-card-row>
            <v-card-row>
                <v-card-text>
                    <v-container fluid>
                        <v-text-field label="Name" required
                                      v-model="company.name"/>
                        <small>*indicates required field</small>
                    </v-container>
                </v-card-text>
            </v-card-row>
            <v-card-row actions>
                <v-btn class="green--text darken-1" flat="flat"
                       @click.native="isOpen = false">Close
                </v-btn>
                <v-btn class="green--text darken-1" flat="flat"
                       @click.native="createCompany()">Save
                </v-btn>
            </v-card-row>
        </v-card>
    </v-dialog>
</template>

<script>
  import eventBus from 'components/event-bus'
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
        this.$http.post('/companies', {
          name: this.company.name
        })
          .then(response => {
            if (response.status === 200) {
              eventBus.$emit('company.created')
              // TODO: toast
            }
          })
          .catch(error => {
            // TODO: toast
          })

        this.isOpen = false
        this.company = {}
      }
    }
  }
</script>