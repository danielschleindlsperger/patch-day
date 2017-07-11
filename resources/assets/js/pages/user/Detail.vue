<template>
    <div>
        <v-container>

            <h1 class="display-1 text-xs-center flex">{{ user.name }}</h1>

            <div class="subheading">Company:
                <router-link :to="'/companies/' + user.company.id">
                    {{ user.company.name }}
                </router-link>
            </div>
            <div class="subheading">Role: {{ user.role }}</div>
            <div class="subheading">E-Mail:
                <a :href="`mailto:${user.email}`">{{ user.email }}</a>
            </div>
        </v-container>

        <fab :user="user" :fabActions="fabActions"></fab>
    </div>
</template>

<script>
  import eventBus from 'components/event-bus'
  import filters from 'mixins/filters'
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
            event: 'user.delete.modal'
          },
          {
            icon: 'add',
            color: 'indigo',
            event: 'user.create.modal',
          },
          {
            icon: 'edit',
            color: 'green',
            event: 'user.edit.modal',
          },
        ],
      }
    },
    mixins: [filters],
    mounted() {
      this.getUser()

      eventBus.$on('user.deleted', item => {
        // this user was deleted
        if (item.id === this.user.id) {
          this.$router.push('/users')
        }
      })

      eventBus.$on('user.edited', user => {
        this.getUser()
      })
    },
    methods: {
      getUser() {
        const ID = this.$route.params.id
        this.$http.get(`/users/${ID}`)
          .then(response => {
            this.user = response.data
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