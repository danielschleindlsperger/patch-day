<template>
    <div>
        <v-container>
            <h1 class="display-2 text-xs-center">Projects</h1>

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
                    </v-list-tile>
                </v-list>
            </v-card>
        </v-container>
    </div>
</template>

<script>
  import eventBus from 'components/event-bus'
  import repo from 'repository'

  export default {
    data() {
      return {
        projects: [],
      }
    },
    beforeRouteEnter (to, from, next) {
      repo.project.getAll().then((projects) => {
        next((vm) => {
          vm.projects = projects
        })
      })
    },
  }
</script>