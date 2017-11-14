<template>
    <div>
        <v-container>

            <h1 class="display-1 text-xs-center flex mb-4">{{ user.name }}</h1>

            <div class="subheading">Company:
                <router-link v-if="user.company" :to="'/companies/' + user.company.id">
                    {{ user.company.name }}
                </router-link>
            </div>
            <div class="subheading">Role: {{ user.role }}</div>
            <div class="subheading">E-Mail:
                <a :href="`mailto:${user.email}`">{{ user.email }}</a>
            </div>
        </v-container>

        <fab :user="user" :fabActions="fabActions" :show="showFab"></fab>
    </div>
</template>

<script>
  import eventBus from 'components/event-bus'
  import filters from 'mixins/filters'
  import repo from 'repository'
  import Fab from 'pages/user/Fab'

  export default {
    components: {
      Fab,
    },
    data() {
      return {
        user: {
          name: '',
          email: '',
          role: '',
          company: {
            name: '',
          },
        },
        fabActions: [
          {
            icon: 'delete',
            color: 'red',
            event: 'user.delete.modal',
            tooltip: 'Delete user',
          },
          {
            icon: 'add',
            color: 'indigo',
            event: 'user.create.modal',
            tooltip: 'Create user',
          },
          {
            icon: 'edit',
            color: 'green',
            event: 'user.edit.modal',
            tooltip: 'Edit user',
          },
        ],
        showFab: false,
      }
    },
    mixins: [filters],
    beforeRouteEnter (to, from, next) {
      repo.user.get(to.params.id).then((user) => {
        next((vm) => {
          vm.user = user
          vm.showFab = true
        })
      })
    },
    mounted() {
      eventBus.$on('user.deleted', id => {
        if (id === this.user.id) {
          this.$router.push('/users')
        }
      })

      eventBus.$on('user.edited', user => {
        this.getUser()
      })
    },
    methods: {
      getUser() {
        repo.user.get(this.user.id).then((user) => {
          this.user = user
        })
      },
    }
  }
</script>