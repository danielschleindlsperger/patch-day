<template>
    <div>
        <v-container>
            <v-layout justify-center child-flex[-sm] class="mb-5">
                <h1 class="display-1 text-xs-center flex">{{ project.name }}</h1>
                <v-btn class="flex"
                       flat="flat" icon ripple
                       @click.native="editProjectModal($event)">
                    <v-icon class="grey--text">
                        mode_edit
                    </v-icon>
                </v-btn>

                <v-btn class="flex"
                       flat="flat" icon ripple
                       @click.native="deleteProject($event)">
                    <v-icon class="grey--text">
                        delete
                    </v-icon>
                </v-btn>
            </v-layout>

            <div class="info-wrapper">
                <div class="headline">
                    Price/PatchDay:
                    {{ project.base_price | currency('EUR', true) }}
                </div>
                <div class="headline mb-4">
                    Penalty:
                    {{ project.penalty | currency('EUR', true) }}
                </div>

                <div class="mb-5">
                    <h3 class="headline mb-0">Technologies</h3>
                    <v-chip class="text-xs-center"
                            v-for="technology in project.current_technologies"
                            :key="technology.id">
                        {{ technology.name }}&nbsp;{{ technology.version }}
                    </v-chip>
                    <div>
                        <v-btn dark default
                               @click.native="techHistoryModal($event)">
                            History
                        </v-btn>
                    </div>
                </div>
            </div>

            <v-card class="projects">
                <v-card-title>
                    <h3 class="text-xs-center display-1 mb-0">PatchDays</h3>
                </v-card-title>
                <v-card-text>
                    <v-data-table
                            :headers="tableHeaders"
                            :items="project.protocols"
                            hide-actions
                    >
                        <template slot="items" scope="props">
                            <td>
                                <router-link
                                        :to="'/protocols/' + props.item.id">
                                    PatchDay
                                    #{{ props.item.patch_day_id }}
                                </router-link>
                            </td>
                            <td class="text-xs-right">
                                {{ props.item.date | Date }}
                            </td>
                            <td class="text-xs-right">
                                <v-icon>
                                    {{ props.item.done | checkIcon }}
                                </v-icon>
                            </td>
                        </template>
                    </v-data-table>
                </v-card-text>
            </v-card>
        </v-container>
        <delete-project></delete-project>
        <edit-project></edit-project>
        <tech-history-modal></tech-history-modal>
    </div>
</template>

<script>
  import eventBus from 'components/event-bus'
  import filters from 'mixins/filters'
  import DeleteProject from 'pages/project/DeleteProject'
  import EditProject from 'pages/project/EditProject'
  import TechHistoryModal from 'components/modals/TechHistoryModal'

  export default {
    components: {
      DeleteProject,
      EditProject,
      TechHistoryModal,
    },
    mixins: [filters],
    data() {
      return {
        project: {
          base_price: 0,
          penalty: 0,
          name: '',
          company: {
            name: ''
          },
          protocols: []
        },
        tableHeaders: [
          {
            text: 'Name',
            left: true,
            value: 'name',
          },
          {
            text: 'Date',
            value: 'date',
            sortable: true,
          },
          {
            text: 'Done',
            value: 'done',
            sortable: true,
          },
        ]
      }
    },
    methods: {
      deleteProject(event) {
        event.preventDefault()
        event.stopPropagation()
        eventBus.$emit('project.delete.modal', this.project)
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

<style lang="scss" scoped>

    .info-item {
        display: flex;
        align-items: center;
        justify-content: center;
        h6 {
            display: inline-block;
        }
    }
</style>