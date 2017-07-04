<template>
    <div>
        <v-container>
            <v-layout justify-center child-flex[-sm] class="mb-5">
                <h1 class="display-1 text-xs-center flex">
                    {{ project.name }}
                </h1>
            </v-layout>

            <project-info :project="project"></project-info>
            <patch-day-table :protocols="project.protocols"></patch-day-table>

        </v-container>
        <tech-history-modal></tech-history-modal>
    </div>
</template>

<script>
  import eventBus from 'components/event-bus'
  import filters from 'mixins/filters'
  import TechHistoryModal from 'components/modals/TechHistoryModal'
  import ProjectInfo from 'pages/project/components/ProjectInfo'
  import PatchDayTable from 'pages/project/components/PatchDayTable'

  export default {
    components: {
      TechHistoryModal,
      ProjectInfo,
      PatchDayTable,
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