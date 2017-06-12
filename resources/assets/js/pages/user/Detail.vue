<template>
    <div>
        <v-layout justify-center child-flex[-sm]>
            <h1 class="display-1 text-xs-center flex">{{ user.name }}</h1>
            <v-btn class="flex"
                   flat="flat" icon ripple
                   @click.native="editUserModal($event)">
                <v-icon class="grey--text">
                    mode_edit
                </v-icon>
            </v-btn>

            <v-btn  class="flex"
                    flat="flat" icon ripple
                   @click.native="deleteUserModal($event)">
                <v-icon class="grey--text">
                    delete
                </v-icon>
            </v-btn>
        </v-layout>

        <div class="subheading">Company:
            <router-link :to="'/companies/' + user.company.id">
                {{ user.company.name }}
            </router-link>
        </div>
        <div class="subheading">Role: {{ user.role }}</div>
        <div class="subheading">E-Mail:
            <a :href="`mailto:${user.email}`">{{ user.email }}</a>
        </div>

        <delete-user></delete-user>
    </div>
</template>

<script>
  import eventBus from 'components/event-bus'
  import filters from 'mixins/filters'
  import DeleteUser from 'pages/user/DeleteUser'

  export default {
    components: {
      DeleteUser,
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
      }
    },
    mixins: [filters],
    mounted() {
      this.getUser()

      eventBus.$on('user.deleted', item => {
        // this user was deleted
        if (item.id === this.user.id) {
          this.$router.push('/projects')
        }
      })
    },
    methods: {
      getUser() {
        const ID = this.$route.params.id
        this.$http.get(`/users/${ID}`)
          .then(response => {
            this.user = response.data
          })
          .catch(error => {
            console.error(error)
            eventBus.$emit('info.snackbar', error.response.data.error)
            if (error.response.status === 404) {
              this.$router.push({name: 'not-found'})
            }
          })
      },
      deleteUserModal(event) {
        event.preventDefault()
        event.stopPropagation()
        eventBus.$emit('user.delete.modal', this.user);
      },
    }
  }
</script>

<style lang="scss" scoped>

</style>