<template>
    <div>
        <v-container>
            <h1 class="display-2 text-xs-center">Companies</h1>

            <v-card>
                <v-list>
                    <v-list-tile avatar v-for="company in companies"
                                 :key="company.id"
                                 :to="'/companies/' + company.id">
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
                </v-list>
            </v-card>
        </v-container>

        <fab :fabActions="fabActions"></fab>
    </div>
</template>

<script>
  import eventBus from 'components/event-bus'
  import Fab from 'pages/company/Fab'

  export default {
    components: {
      Fab,
    },
    data() {
      return {
        fabActions: [
          {
            icon: 'add',
            color: 'indigo',
            event: 'company.create.modal',
          },
        ],
        companies: [],
        modalOpen: false,
      }
    },
    mounted() {
      this.getCompanies()

      eventBus.$on('company.deleted', payload => {
        const COMPANY_ID = payload[0].id
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
            eventBus.$emit('page.loading', false)
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
    }
  }
</script>