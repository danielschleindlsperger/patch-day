<template>
    <div>
        <v-container>
            <v-card>
                <v-card-text>
                    <div class="button-row">
                        <v-btn primary dark
                               @click.native="openCreateCompanyModal">
                            <v-icon class="white--text text--darken-2">
                                add_circle
                            </v-icon>
                        </v-btn>
                    </div>
                    <v-list>
                        <v-list-item v-for="company in companies"
                                     :key="company.id">
                            <v-list-tile avatar router
                                         :href="'/companies/' + company.id">
                                <v-list-tile-avatar>
                                    <v-icon>business</v-icon>
                                </v-list-tile-avatar>
                                <v-list-tile-content>
                                    <v-list-tile-title>{{ company.name }}
                                    </v-list-tile-title>
                                </v-list-tile-content>
                                <v-list-tile-action>
                                    <v-btn icon ripple
                                           @click.native="deleteItem($event, company)">
                                        <v-icon class="grey--text">
                                            delete
                                        </v-icon>
                                    </v-btn>
                                </v-list-tile-action>
                            </v-list-tile>
                        </v-list-item>
                    </v-list>
                </v-card-text>
            </v-card>
        </v-container>

        <delete-company></delete-company>
        <create-company></create-company>
    </div>
</template>

<script>
  import eventBus from 'components/event-bus'

  import DeleteCompany from 'pages/company/DeleteCompany'
  import CreateCompany from 'pages/company/CreateCompany'

  export default {
    components: {
      DeleteCompany,
      CreateCompany,
    },
    data() {
      return {
        companies: [],
        deleteCompany: {},
        modalOpen: false,
      }
    },
    mounted() {
      this.getCompanies()

      eventBus.$on('company.deleted', payload => {
        const COMPANY_ID = payload[0].id
        console.log(COMPANY_ID)
        // remove deleted item from list
        let newList = this.companies.filter((company) => {
          return company.id !== COMPANY_ID
        })
        this.companies = newList
      })

      eventBus.$on('company.created', () => {
        this.getCompanies()
      })
    },
    methods: {
      getCompanies() {
        this.$http.get('/companies')
          .then(response => {
            this.companies = response.data
          })
          .catch(error => {
            error.response.data
          })
      },
      deleteItem(event, item) {
        event.preventDefault()
        event.stopPropagation()
        eventBus.$emit('company.delete.modal', item);
      },
      openCreateCompanyModal() {
        event.preventDefault()
        event.stopPropagation()
        eventBus.$emit('company.create.modal')
      }
    }
  }
</script>

<style lang="scss" scoped>
    .button-row {
        display: flex;
        flex-flow: row nowrap;
        justify-content: flex-end;
    }
</style>