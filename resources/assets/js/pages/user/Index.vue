<template>
    <v-container>
        <h1 class="display-2 text-xs-center">Users</h1>
        <v-data-table
                :loading="loading"
                v-bind:headers="tableHeaders"
                :items="users"
                hide-actions
                class="elevation-1"
        >
            <template slot="items" scope="props">
                <td>
                    <router-link :to="'/users/' + props.item.id">
                        {{ props.item.name }}
                    </router-link>
                </td>
                <td >
                    <router-link :to="'/companies/' + props.item.company.id">
                        {{ props.item.company.name }}
                    </router-link>
                </td>
                <td class="text-xs-right">{{
                  props.item.created_at | DateTime }}
                </td>
            </template>
        </v-data-table>
    </v-container>
</template>

<script>
  import eventBus from 'components/event-bus'
  import filters from 'mixins/filters'

  export default {
    components: {},
    data() {
      return {
        loading: true,
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
    }
  }
</script>

<style lang="scss" scoped>

</style>