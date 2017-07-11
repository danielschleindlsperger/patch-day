<template>
    <div>
        <v-container>
            <h1 class="display-2 text-xs-center">Projects</h1>

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

        <fab :fabActions="fabActions"></fab>
    </div>
</template>

<script>
  import eventBus from 'components/event-bus'
  import Fab from 'pages/project/components/Fab'

  export default {
    components: {
      Fab,
    },
    data() {
      return {
        fabActions: [
          {
            icon: 'add',
            color: 'indigo',
            event: 'project.create.modal',
          },
        ],
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
    }
  }
</script>