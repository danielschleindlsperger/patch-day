<template>
    <div>
        <v-container>
            <h1 class="display-2 text-xs-center">Projects</h1>
            <v-fab-transition>
                <v-btn
                        class="primary"
                        fab
                        dark
                        fixed
                        bottom
                        right
                        v-show="!fab.hidden"
                        @click.native="openCreateProjectModal($event)"
                >
                    <v-icon>add</v-icon>
                </v-btn>
            </v-fab-transition>

            <v-card>
                <v-list>
                    <v-list-tile avatar v-for="project in projects"
                                 :key="project.id"
                                 :to="'/projects/' + project.id">
                        <v-list-tile-avatar>
                            <v-icon>business</v-icon>
                        </v-list-tile-avatar>
                        <v-list-tile-content>
                            <v-list-tile-title>
                                {{ project.name }}
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
                </v-list>
            </v-card>
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
        fab: {
          hidden: true,
        },
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
            this.fab.hidden = false
            eventBus.$emit('page.loading', false)
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