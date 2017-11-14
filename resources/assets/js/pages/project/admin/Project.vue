<template>
    <div>
        <v-container>
            <h1 class="display-1 text-xs-center mb-4">
                {{ project.name }}
            </h1>

            <v-layout>
                <project-info :project="project"
                              class="mb-4"></project-info>
            </v-layout>
            <patch-day-table :protocols="project.protocols"></patch-day-table>

        </v-container>
        <tech-history-modal></tech-history-modal>
        <fab :project="project" :fabActions="fabActions" :show="showFab"></fab>
    </div>
</template>

<script>
  import eventBus from 'components/event-bus'
  import filters from 'mixins/filters'
  import repo from 'repository'
  import Fab from 'pages/project/components/Fab'
  import TechHistoryModal from 'components/modals/TechHistoryModal'
  import ProjectInfo from 'pages/project/components/ProjectInfo'
  import PatchDayTable from 'pages/project/components/PatchDayTable'

  export default {
    components: {
      TechHistoryModal,
      Fab,
      ProjectInfo,
      PatchDayTable,
    },
    mixins: [filters],
    data() {
      return {
        fabActions: [
          {
            icon: 'delete',
            color: 'red',
            event: 'project.delete.modal',
            tooltip: 'Delete Project',
          },
          {
            icon: 'add',
            color: 'indigo',
            event: 'project.create.modal',
            tooltip: 'Create Project',
          },
          {
            icon: 'edit',
            color: 'green',
            event: 'project.edit.modal',
            tooltip: 'Edit Project',
          },
        ],
        showFab: false,
        project: {
          base_price: 0,
          penalty: 0,
          name: '',
          company: {
            name: ''
          },
        },
      }
    },
    beforeRouteEnter (to, from, next) {
      repo.project.get(to.params.id).then((project) => {
        next((vm) => {
          vm.project = project
          vm.showFab = true
        })
      })
    },
    methods: {
      techHistoryModal(event) {
        event.preventDefault()
        event.stopPropagation()
        eventBus.$emit('tech_history.view.modal', this.project.technology_history)
      },
      getProject() {
        repo.project.get(this.project.id).then((project) => {
          this.project = project
        })
      }
    },
    mounted() {
      eventBus.$on('project.deleted', (id) => {
        if (id === this.project.id) {
          this.$router.push('/projects')
        }
      }).$on('project.edited', () => {
        this.getProject()
      }).$on('technology.deleted', () => {
        this.getProject()
      })
    }
  }
</script>