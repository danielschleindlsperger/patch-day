<template>
    <div>
        <v-container>
            <h1 class="display-2 text-xs-center">{{ company.name }}</h1>
            <h3 class="headline">Projects</h3>
            <v-card>
                <v-list>
                    <v-list-tile v-for="item in company.projects"
                                 :key="item.id"
                                 :to="'/projects/' + item.id">
                        <v-list-tile-content>
                            <v-list-tile-title>{{ item.name }}
                            </v-list-tile-title>
                        </v-list-tile-content>
                        <v-list-tile-action>
                            <v-btn icon ripple
                                   @click.native="deleteCompanyModal($event,
                                           item)">
                                <v-icon class="grey--text">
                                    delete
                                </v-icon>
                            </v-btn>
                        </v-list-tile-action>
                    </v-list-tile>
                </v-list>
            </v-card>
        </v-container>

        <fab :company="company" :fabActions="fabActions"></fab>
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
            icon: 'delete',
            color: 'red',
            event: 'company.delete.modal'
          },
          {
            icon: 'add',
            color: 'indigo',
            event: 'company.create.modal',
          },
          {
            icon: 'edit',
            color: 'green',
            event: 'company.edit.modal',
          },
        ],
        company: {},
        projects: [],
      }
    },
    mounted() {
      eventBus.$on('company.deleted', (payload) => {
        // this company was deleted
        if (payload[0].id === this.company.id) {
          this.$router.push('/companies')
        }
      })

      const companyId = this.$route.params.id
      this.$http.get(`/companies/${companyId}`)
        .then(response => {
          this.company = response.data
          eventBus.$emit('page.loading', false)
        })
        .catch(error => {
          console.log(error.response.data)
          eventBus.$emit('info.snackbar', error.response.data.error)
          if (error.response.status === 404) {
            this.$router.push({name: 'not-found'})
          }
        })
    }
  }
</script>