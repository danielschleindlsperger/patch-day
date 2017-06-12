<template>
    <div>
        <v-container>
            <h1 class="display-2 text-xs-center">Users</h1>

            <!-- TODO: fix search for company name -->
            <div class="action-wrapper">
                <v-text-field
                        append-icon="search"
                        label="Search"
                        single-line
                        hide-details
                        v-model="search"
                ></v-text-field>
                <v-btn icon class="ml-4"
                       @click.native="openCreateUserModal($event)">
                    <v-icon class="grey--text">
                        add_circle
                    </v-icon>
                </v-btn>
            </div>

            <v-data-table
                    :loading="loading"
                    :headers="tableHeaders"
                    :items="users"
                    :search="search"
                    hide-actions
                    class="elevation-1"
            >
                <template slot="items" scope="props">
                    <td>
                        <router-link :to="'/users/' + props.item.id">
                            {{ props.item.name }}
                        </router-link>
                    </td>
                    <td>
                        <router-link
                                :to="'/companies/' + props.item.company.id">
                            {{ props.item.company.name }}
                        </router-link>
                    </td>
                    <td>
                        {{ props.item.role }}
                    </td>
                    <td class="text-xs-right">
                        {{ props.item.created_at | DateTime }}
                    </td>
                </template>
            </v-data-table>
        </v-container>
        <create-user></create-user>
    </div>
</template>

<script>
  import eventBus from 'components/event-bus'
  import filters from 'mixins/filters'
  import CreateUser from 'pages/user/CreateUser'

  export default {
    components: {
      CreateUser
    },
    data() {
      return {
        loading: true,
        search: '',
        users: [
          {
            name: '',
            created_at: '',
            company: {
              name: '',
            },
          },
        ],
        tableHeaders: [
          {
            text: 'Name',
            left: true,
            value: 'name',
          },
          {
            text: 'Company',
            value: 'company.name',
            sortable: true,
            left: true,
          },
          {
            text: 'Role',
            value: 'role',
            sortable: true,
            left: true,
          },
          {
            text: 'Created',
            value: 'id',
            sortable: true,
          },
        ]
      }
    },
    mixins: [filters],
    mounted() {
      this.getUsers()
    },
    methods: {
      getUsers() {
        this.$http.get('/users')
          .then(response => {
            this.users = response.data
            this.loading = false
          })
          .catch(error => {
            console.error(error)
            eventBus.$emit('info.snackbar', error.response.data.error)
            if (error.response.status === 404) {
              this.$router.push({name: 'not-found'})
            }
          })
      },
      openCreateUserModal(event) {
        event.preventDefault()
        event.stopPropagation()
        eventBus.$emit('user.create.modal')
      }
    }
  }
</script>

<style lang="scss" scoped>
    .action-wrapper {
        display: flex;
        justify-content: flex-end;
        align-items: center;
        > div {
            width: 100%;
            max-width: 200px;
        }
    }
</style>