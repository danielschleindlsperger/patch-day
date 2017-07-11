<template>
    <div>
        <v-container>
            <h1 class="display-2 text-xs-center">Users</h1>
            <user-table :users="users"></user-table>
        </v-container>
        <fab :fabActions="fabActions"></fab>
    </div>
</template>

<script>
  import eventBus from 'components/event-bus'
  import Fab from 'pages/user/Fab'
  import UserTable from 'pages/user/components/UserTable'

  export default {
    components: {
      Fab,
      UserTable,
    },
    data() {
      return {
        search: '',
        fabActions: [
          {
            icon: 'add',
            color: 'indigo',
            event: 'user.create.modal',
          },
        ],
        users: [
          {
            name: '',
            created_at: '',
            company: {
              name: '',
            },
          },
        ],
      }
    },
    mounted() {
      this.getUsers()
    },
    methods: {
      getUsers() {
        this.$http.get('/users')
          .then(response => {
            this.users = response.data
            eventBus.$emit('page.loading', false)
          })
          .catch(error => {
            console.error(error)
            eventBus.$emit('info.snackbar', error.response.data.error)
            if (error.response.status === 404) {
              this.$router.push({name: 'not-found'})
            }
          })
      },
    }
  }
</script>