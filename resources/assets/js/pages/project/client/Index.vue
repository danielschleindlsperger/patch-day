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
                    </v-list-tile>
                </v-list>
            </v-card>
        </v-container>
    </div>
</template>

<script>
  import eventBus from 'components/event-bus'

  export default {
    data() {
      return {
        projects: [],
        deleteProject: {},
        modalOpen: false,
      }
    },
    mounted() {
      this.getProjects()
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
    }
  }
</script>