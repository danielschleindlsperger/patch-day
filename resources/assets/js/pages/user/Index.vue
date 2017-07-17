<template>
    <div>
        <v-container>
            <h1 class="display-2 text-xs-center">Users</h1>
            <user-table :users="users"></user-table>
        </v-container>
        <fab :fabActions="fabActions" :show="showFab"></fab>
    </div>
</template>

<script>
  import eventBus from 'components/event-bus'
  import repo from 'repository'
  import Fab from 'pages/user/Fab'
  import UserTable from 'pages/user/components/UserTable'

  export default {
    components: {
      Fab,
      UserTable,
    },
    beforeRouteEnter (to, from, next) {
      repo.user.getAll().then((users) => {
        next((vm) => {
          vm.users = users
          vm.showFab = true
        })
      })
    },
    data() {
      return {
        search: '',
        fabActions: [
          {
            icon: 'add',
            color: 'indigo',
            event: 'user.create.modal',
            tooltip: 'Create user',
          },
        ],
        showFab: false,
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
      eventBus.$on('user.created', () => {
        this.getUsers()
      })
    },
    methods: {
      getUsers() {
        repo.user.getAll().then((users) => {
          this.users = users
        })
      }
    }
  }
</script>