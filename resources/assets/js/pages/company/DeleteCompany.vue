<template>
    <v-dialog v-model="isOpen">
        <v-card>
            <v-card-row>
                <v-card-title>Delete {{ company.name }} ?</v-card-title>
            </v-card-row>
            <v-card-row>
                <v-card-text>
                    Deleting {{ company.name
                    }} may have sideeffects and the deletion may not be reversible.
                </v-card-text>
            </v-card-row>
            <v-card-row actions>
                <v-btn class="green--text darken-1" flat="flat"
                       @click.native="isOpen = false">PLs no
                </v-btn>
                <v-btn error flat="flat"
                       @click.native="deleteCompany">Delete
                </v-btn>
            </v-card-row>
        </v-card>
    </v-dialog>
</template>

<script>
  import eventBus from 'components/event-bus'
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
        this.$http.delete(`/companies/${this.company.id}`)
          .then(response => {
            if (response.status === 200) {
              eventBus.$emit('company.deleted', [this.company])
            }
          })
          .catch(error => {

          })
      }
    }
  }
</script>