<template>
    <div>
        <v-container>
            <h1 class="display-2 text-xs-center mb-4">Projects</h1>

            <v-card v-if="projects.length">
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

        <fab :fabActions="fabActions" :show="showFab"></fab>
    </div>
</template>

<script>
  import eventBus from 'components/event-bus'
  import Fab from 'pages/project/components/Fab'
  import repo from 'repository'

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
            tooltip: 'Create Project',
          },
        ],
        showFab: false,
        projects: [],
        deleteProject: {},
        modalOpen: false,
      }
    },
    beforeRouteEnter (to, from, next) {
      repo.project.getAll().then((projects) => {
        next((vm) => {
          vm.projects = projects
          vm.showFab = true
        })
      })
    },
    mounted() {
      eventBus.$on('project.deleted', () => {
        this.getProjects()
      }).$on('project.created', () => {
        this.getProjects()
      })
    },
    methods: {
      getProjects() {
        repo.project.getAll().then((projects) => {
          this.projects = projects
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