<template>
    <v-container>
        <h1 class="display-1 text-xs-center">{{ user.name }}</h1>

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
</template>

<script>
  import eventBus from 'components/event-bus'
  import filters from 'mixins/filters'

  export default {
    components: {},
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
    }
  }
</script>

<style lang="scss" scoped>

</style>