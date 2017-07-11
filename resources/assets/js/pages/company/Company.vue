<template>
    <div>
        <v-container>
            <v-fab-transition>
                <v-speed-dial
                        bottom
                        right
                        fixed
                        transition="slide-y-reverse-transition"
                        v-model="fab.dialOpen"
                        v-show="!fab.hidden"
                >

                    <v-btn
                            slot="activator"
                            class="blue darken-2"
                            dark
                            fab
                            hover
                            v-model="fab.dialOpen"
                    >
                        <v-icon>keyboard_arrow_up</v-icon>
                        <v-icon>close</v-icon>
                    </v-btn>


                    <v-btn
                            fab
                            dark
                            small
                            class="green"
                            @click.native="editCompanyModal($event, company)"
                    >
                        <v-icon>edit</v-icon>
                    </v-btn>

                    <v-btn
                            fab
                            dark
                            small
                            class="indigo"
                            @click.native="createCompanyModal($event, company)"
                    >
                        <v-icon>add</v-icon>
                    </v-btn>

                    <v-btn
                            fab
                            dark
                            small
                            class="red"
                            @click.native="deleteCompanyModal($event, company)"
                    >
                        <v-icon>delete</v-icon>
                    </v-btn>

                </v-speed-dial>
            </v-fab-transition>

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
        <delete-company></delete-company>
        <edit-company :company="company"></edit-company>
        <create-company></create-company>
    </div>
</template>

<script>
  import eventBus from 'components/event-bus'
  import DeleteCompany from 'pages/company/DeleteCompany'
  import EditCompany from 'pages/company/EditCompany'
  import CreateCompany from 'pages/company/CreateCompany'

  export default {
    components: {
      DeleteCompany,
      EditCompany,
      CreateCompany,
    },
    data() {
      return {
        fab: {
          hidden: true,
          dialOpen: false,
        },
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
      createCompanyModal(event, item) {
        event.preventDefault()
        event.stopPropagation()
        eventBus.$emit('company.create.modal');
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
          this.fab.hidden = false
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