<template>
    <div>
        <v-container>
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
                    </v-list-tile>
                </v-list-item>
            </v-list>
        </v-container>
    </div>
</template>

<script>
  import eventBus from 'components/event-bus'

  export default {
    data() {
      return {
        projects: [],
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