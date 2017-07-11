<template>
    <div>
        <v-container>
            <div class="card-head">
                <h2 class="text-xs-center">{{ company.name }}</h2>
                <div class="button-row">
                    <v-btn flat="flat" icon ripple
                           @click.native="editCompanyModal($event,
                                   company)">
                        <v-icon class="grey--text">
                            mode_edit
                        </v-icon>
                    </v-btn>
                    <v-btn flat="flat" icon ripple
                           @click.native="deleteCompany($event, company)">
                        <v-icon class="grey--text">
                            delete
                        </v-icon>
                    </v-btn>
                </div>
            </div>
            <div class="projects">
                <h3 class="text-xs-center">Projects</h3>
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
            </div>
        </v-container>
        <delete-company></delete-company>
        <edit-company :company="company"></edit-company>
    </div>
</template>

<script>
  import eventBus from 'components/event-bus'
  import DeleteCompany from 'pages/company/DeleteCompany'
  import EditCompany from 'pages/company/EditCompany'

  export default {
    components: {
      DeleteCompany,
      EditCompany,
    },
    data() {
      return {
        company: {},
        projects: [],
      }
    },
    methods: {
      deleteCompanyModal(event, item) {
        event.preventDefault()
        event.stopPropagation()
        eventBus.$emit('company.delete.modal', item);
      },
      editCompanyModal(event, item) {
        event.preventDefault()
        event.stopPropagation()
        eventBus.$emit('company.edit.modal', item);
      },
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
          console.log(response.data)
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

<style lang="scss" scoped>
    h1 {
        font-size: 48px;
        text-align: center;
    }

    .button-row {
        display: flex;
        flex-flow: row nowrap;
        justify-content: flex-end;
    }

    hr {
        margin: 1rem 0 3rem;
    }
</style>