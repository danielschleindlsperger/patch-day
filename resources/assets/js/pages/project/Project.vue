<template>
    <div>
        <v-container>
            <v-card>
                <v-card-text>
                    <div class="card-head">
                        <h2 class="text-xs-center">{{ project.name }}</h2>
                        <div class="info-wrapper">
                            <h3>{{ project.company.name }}</h3>
                            <div class="button-row">
                                <v-btn flat="flat" icon ripple
                                       @click.native="editProjectModal($event,
                                   project)">
                                    <v-icon class="grey--text">
                                        mode_edit
                                    </v-icon>
                                </v-btn>
                                <v-btn flat="flat" icon ripple
                                       @click.native="deleteProject($event,
                                   project)">
                                    <v-icon class="grey--text">
                                        delete
                                    </v-icon>
                                </v-btn>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="projects">
                        <h3 class="text-xs-center">PatchDays</h3>
                        <v-list>
                            <v-list-item v-for="item in patchDays"
                                         :key="item.id">
                                <v-list-tile avatar router
                                             :href="'/patch-days/' + item.id">
                                    <v-list-tile-content>
                                        <v-list-tile-title>{{ item.name }}
                                        </v-list-tile-title>
                                    </v-list-tile-content>
                                    <v-list-tile-action>
                                        <v-btn icon ripple
                                               @click.native="deletePatchDay($event,
                                           item)">
                                            <v-icon class="grey--text">
                                                delete
                                            </v-icon>
                                        </v-btn>
                                    </v-list-tile-action>
                                </v-list-tile>
                            </v-list-item>
                        </v-list>
                    </div>
                </v-card-text>
            </v-card>
        </v-container>
        <delete-project></delete-project>
        <edit-project :project="project"></edit-project>
    </div>
</template>

<script>
  import eventBus from 'components/event-bus'
  import DeleteProject from 'pages/project/DeleteProject'
  import EditProject from 'pages/project/EditProject'

  export default {
    components: {
      DeleteProject,
      EditProject,
    },
    data() {
      return {
        project: {
          company: {
            name: ''
          }
        },
        patchDays: [],
      }
    },
    methods: {
      deleteProject(event, item) {
        event.preventDefault()
        event.stopPropagation()
        eventBus.$emit('project.delete.modal', item);
      },
      editProjectModal(event, item) {
        event.preventDefault()
        event.stopPropagation()
        eventBus.$emit('project.edit.modal', item);
      },
    },
    mounted() {
      eventBus.$on('project.deleted', item => {
        // this company was deleted
        if (item.id === this.project.id) {
          this.$router.push('/projects')
        }
      })

      const projectId = this.$route.params.id
      this.$http.get(`/projects/${projectId}`)
        .then(response => {
          this.project = response.data
          console.log(response.data)
        })
        .catch(error => {
          console.error(error)
          eventBus.$emit('info.snackbar', error.response.data.error)
          if (error.response.status === 404) {
            this.$router.push({name: 'not-found'})
          }
        })

      this.$http.get(`/projects/${projectId}/patch-days`)
        .then(response => {
          this.patchDays = response.data
          console.log(response.data)
        })
        .catch(error => {
          console.error(error)
          eventBus.$emit('info.snackbar', error.response.data.error)
        })
    }
  }
</script>

<style lang="scss" scoped>
    h1 {
        font-size: 48px;
        text-align: center;
    }

    .info-wrapper {
        display: flex;
        flex-flow: row nowrap;
        justify-content: space-between;
        h3 {
            font-size: 32px;
        }
        .button-row {
            display: flex;
            flex-flow: row nowrap;
            justify-content: flex-end;
        }
    }

    hr {
        margin: 1rem 0 3rem;
    }
</style>