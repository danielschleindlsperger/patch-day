<template>
    <div>
        <v-container>
            <h1 class="display-1 text-xs-center flex">
                {{ project.name }}
            </h1>

            <v-fab-transition>
                <v-speed-dial
                        bottom
                        right
                        fixed
                        transition="slide-y-reverse-transition"
                        v-model="fab.dialOpen"
                        v-show="!fab.hidden"
                >

                    <v-btn
                            slot="activator"
                            class="blue darken-2"
                            dark
                            fab
                            hover
                            v-model="fab.dialOpen"
                    >
                        <v-icon>keyboard_arrow_up</v-icon>
                        <v-icon>close</v-icon>
                    </v-btn>


                    <v-btn
                            fab
                            dark
                            small
                            class="green"
                            @click.native="editProjectModal($event, project)"
                    >
                        <v-icon>edit</v-icon>
                    </v-btn>

                    <v-btn
                            fab
                            dark
                            small
                            class="indigo"
                            @click.native="createProjectModal($event)"
                    >
                        <v-icon>add</v-icon>
                    </v-btn>

                    <v-btn
                            fab
                            dark
                            small
                            class="red"
                            @click.native="deleteProjectModal($event, project)"
                    >
                        <v-icon>delete</v-icon>
                    </v-btn>

                </v-speed-dial>
            </v-fab-transition>

            <v-layout>
                <project-info :project="project"
                              class="mb-4 pa-4"></project-info>
            </v-layout>
            <patch-day-table :protocols="project.protocols"></patch-day-table>

        </v-container>
        <create-project></create-project>
        <delete-project></delete-project>
        <edit-project></edit-project>
        <tech-history-modal></tech-history-modal>
    </div>
</template>

<script>
  import eventBus from 'components/event-bus'
  import filters from 'mixins/filters'
  import CreateProject from 'pages/project/modals/CreateProject'
  import DeleteProject from 'pages/project/modals/DeleteProject'
  import EditProject from 'pages/project/modals/EditProject'
  import TechHistoryModal from 'components/modals/TechHistoryModal'
  import ProjectInfo from 'pages/project/components/ProjectInfo'
  import PatchDayTable from 'pages/project/components/PatchDayTable'

  export default {
    components: {
      DeleteProject,
      CreateProject,
      EditProject,
      TechHistoryModal,
      ProjectInfo,
      PatchDayTable,
    },
    mixins: [filters],
    data() {
      return {
        fab: {
          hidden: false,
          dialOpen: false,
        },
        project: {
          base_price: 0,
          penalty: 0,
          name: '',
          company: {
            name: ''
          },
          protocols: []
        },
      }
    },
    methods: {
      deleteProjectModal(event) {
        event.preventDefault()
        event.stopPropagation()
        eventBus.$emit('project.delete.modal', this.project)
      },
      createProjectModal(event) {
        event.preventDefault()
        event.stopPropagation()
        eventBus.$emit('project.create.modal', this.project)
      },
      editProjectModal(event) {
        event.preventDefault()
        event.stopPropagation()
        eventBus.$emit('project.edit.modal', this.project)
      },
      techHistoryModal(event) {
        event.preventDefault()
        event.stopPropagation()
        eventBus.$emit('tech_history.view.modal', this.project.technology_history)
      },
    },
    mounted() {
      eventBus.$on('project.deleted', item => {
        // this project was deleted
        if (item.id === this.project.id) {
          this.$router.push('/projects')
        }
      })

      eventBus.$on('project.edited', project => {
        this.project = JSON.parse(JSON.stringify(project))
      })

      const ID = this.$route.params.id
      this.$http.get(`/projects/${ID}`)
        .then(response => {
          this.project = response.data
          this.fab.hidden = false
          eventBus.$emit('page.loading', false)
        })
        .catch(error => {
          console.error(error)
          eventBus.$emit('info.snackbar', error.response.data.error)
          if (error.response.status === 404) {
            this.$router.push({name: 'not-found'})
          }
        })
    }
  }
</script>