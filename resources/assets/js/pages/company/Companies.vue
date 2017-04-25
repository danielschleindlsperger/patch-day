<template>
    <div>
        <v-card>
            <v-card-text>
                <div class="button-row">
                    <v-btn primary dark>
                        <v-icon class="white--text text--darken-2">add_circle
                        </v-icon>
                    </v-btn>
                </div>
                <v-list>
                    <v-list-item v-for="item in list" :key="item.id">
                        <v-list-tile avatar router :href="item.href">
                            <v-list-tile-avatar>
                                <v-icon>business</v-icon>
                            </v-list-tile-avatar>
                            <v-list-tile-content>
                                <v-list-tile-title>{{ item.name }}
                                </v-list-tile-title>
                            </v-list-tile-content>
                            <v-list-tile-action>
                                <v-btn icon ripple
                                       @click.native="deleteItem($event, item)">
                                    <v-icon class="grey--text">
                                        delete
                                    </v-icon>
                                </v-btn>
                            </v-list-tile-action>
                        </v-list-tile>
                    </v-list-item>
                </v-list>
            </v-card-text>
        </v-card>
        <delete-company></delete-company>
    </div>
</template>

<script>
  import eventBus from 'components/event-bus'

  import DeleteCompany from 'pages/company/DeleteCompany'

  export default {
    components: {
      DeleteCompany,
    },
    data() {
      return {
        list: [],
        deleteCompany: {},
        modalOpen: false,
      }
    },
    mounted() {
      this.$http.get('/companies')
        .then(response => {
          this.list = response.data
          this.addLinks()
          console.log(response.data)
        })
        .catch(error => {
          error.response.data
        })

      eventBus.$on('company.deleted', payload => {
        const COMPANY_ID = payload[0].id
        console.log(COMPANY_ID)
        // remove deleted item from list
        let newList = this.list.filter((item) => {
          return item.id !== COMPANY_ID
        })
        this.list = newList
      })
    },
    methods: {
      addLinks() {
        this.list.forEach(item => {
          item.href = `/companies/${item.id}`
        })
      },
      deleteItem(event, item) {
        event.preventDefault()
        event.stopPropagation()
        eventBus.$emit('company.delete.modal', item);
      }
    }
  }
</script>

<style lang="scss" scoped>
    .button-row {
        display: flex;
        flex-flow: row nowrap;
        justify-content: flex-end;
    }
</style>