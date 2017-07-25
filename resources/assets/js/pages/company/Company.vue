<template>
    <div>
        <v-container>
            <v-layout justify-center align-center class="mb-2">
                <img :src="company.logo" alt="" class="logo">
                <h1 class="display-2 text-xs-center mb-0">{{ company.name }}
                </h1>
            </v-layout>
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

        <fab :company="company" :fabActions="fabActions" :show="showFab"></fab>
    </div>
</template>

<script>
  import eventBus from 'components/event-bus'
  import Fab from 'pages/company/Fab'
  import repo from 'repository'

  export default {
    components: {
      Fab,
    },
    data() {
      return {
        fabActions: [
          {
            icon: 'delete',
            color: 'red',
            event: 'company.delete.modal',
            tooltip: 'Delete Company',
          },
          {
            icon: 'add',
            color: 'indigo',
            event: 'company.create.modal',
            tooltip: 'Create Company',
          },
          {
            icon: 'edit',
            color: 'green',
            event: 'company.edit.modal',
            tooltip: 'Edit Company',
          },
        ],
        showFab: false,
        company: {},
        projects: [],
      }
    },
    beforeRouteEnter (to, from, next) {
      repo.company.get(to.params.id).then((company) => {
        next((vm) => {
          vm.company = company
          vm.showFab = true
        })
      })
    },
    mounted() {
      eventBus.$on('company.deleted', (id) => {
        // this company was deleted
        if (id === this.company.id) {
          this.$router.push('/companies')
        }
      })
    }
  }
</script>

<style lang="scss" scoped>
    .logo {
        height: 40px;
        border-radius: 50%;
        margin-right: 1em;
    }
</style>