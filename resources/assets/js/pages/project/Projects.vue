<template>
    <div>
        <v-container>
            <div class="button-row">
                <v-btn primary dark
                       @click.native="openCreateProjectModal($event)">
                    <v-icon class="white--text text--darken-2">
                        add_circle
                    </v-icon>
                </v-btn>
            </div>
            <v-list>
                <v-list-item v-for="project in projects"
                             :key="project.id">
                    <v-list-tile avatar router
                                 :href="'/projects/' + project.id">
                        <v-list-tile-avatar>
                            <v-icon>business</v-icon>
                        </v-list-tile-avatar>
                        <v-list-tile-content>
                            <v-list-tile-title>{{ project.name }}
                            </v-list-tile-title>
                        </v-list-tile-content>
                        <v-list-tile-action>
                            <v-btn icon ripple
                                   @click.native="deleteItem($event, project)">
                                <v-icon class="grey--text">
                                    delete
                                </v-icon>
                            </v-btn>
                        </v-list-tile-action>
                    </v-list-tile>
                </v-list-item>
            </v-list>
        </v-container>

        <delete-project></delete-project>
        <create-project></create-project>
    </div>
</template>

<script>
  import eventBus from 'components/event-bus'

  import DeleteProject from 'pages/project/modals/DeleteProject'
  import CreateProject from 'pages/project/modals/CreateProject'

  export default {
    components: {
      DeleteProject,
      CreateProject,
    },
    data() {
      return {
        projects: [],
        deleteProject: {},
        modalOpen: false,
      }
    },
    mounted() {
      this.getProjects()

      eventBus.$on('project.deleted', project => {
        const PROJECT_ID = project.id
        // remove deleted item from list
        let newList = this.projects.filter((item) => {
          return item.id !== PROJECT_ID
        })
        this.projects = newList
      })

      eventBus.$on('project.created', () => {
        this.getProjects()
      })
    },
    methods: {
      getProjects() {
        this.$http.get('/projects')
          .then(response => {
            this.projects = response.data
          })
          .catch(error => {
            error.response.data
          })
      },
      deleteItem(event, item) {
        event.preventDefault()
        event.stopPropagation()
        eventBus.$emit('project.delete.modal', item);
      },
      openCreateProjectModal(event) {
        event.preventDefault()
        event.stopPropagation()
        eventBus.$emit('project.create.modal')
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