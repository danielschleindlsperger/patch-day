<template>
    <div>
        <v-container>
            <h1 class="display-1 text-xs-center mb-5">
                {{ project.name }}
            </h1>

            <v-layout>
                <project-info :project="project"
                              class="mb-4 pa-4"></project-info>
            </v-layout>
            <patch-day-table :protocols="project.protocols"></patch-day-table>

        </v-container>
        <tech-history-modal></tech-history-modal>
        <fab :project="project" :fabActions="fabActions"></fab>
    </div>
</template>

<script>
  import eventBus from 'components/event-bus'
  import filters from 'mixins/filters'
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
            event: 'project.delete.modal'
          },
          {
            icon: 'add',
            color: 'indigo',
            event: 'project.create.modal',
          },
          {
            icon: 'edit',
            color: 'green',
            event: 'project.edit.modal',
          },
        ],
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