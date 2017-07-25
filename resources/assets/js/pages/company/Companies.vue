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
                            <img :src="company.logo" :alt="company.name">
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

        <fab :fabActions="fabActions" :show="showFab"></fab>
    </div>
</template>

<script>
  import eventBus from 'components/event-bus'
  import repo from 'repository'
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
            tooltip: 'Create Company',
          },
        ],
        showFab: false,
        companies: [],
        modalOpen: false,
      }
    },
    beforeRouteEnter (to, from, next) {
      repo.company.getAll().then((companies) => {
        next((vm) => {
          vm.companies = companies
          vm.showFab = true
        })
      })
    },
    mounted() {
      eventBus.$on('company.deleted', payload => {
        this.getCompanies()
      }).$on('company.created', () => {
        this.getCompanies()
      })
    },
    methods: {
      getCompanies() {
        repo.company.getAll().then((companies) => {
          this.companies = companies
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