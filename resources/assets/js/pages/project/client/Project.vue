<template>
    <div>
        <v-container>
            <v-layout justify-center child-flex[-sm] class="mb-5">
                <h1 class="display-1 text-xs-center flex">
                    {{ project.name }}
                </h1>
            </v-layout>

            <v-layout mb-5>

                <v-flex xs12 lg6>
                    <project-info :project="project"></project-info>
                </v-flex>

                <v-flex xs12 lg6>
                    <v-card>
                        <v-card-row class="primary">
                            <v-card-title>
                                <span class="white--text">Project Actions</span>
                            </v-card-title>
                        </v-card-row>
                        <v-card-text>
                            <v-btn primary light
                                   @click.native="signupModal($event)">
                                Sign up for PatchDay
                            </v-btn>
                            <v-btn primary light
                                   @click.native="cancelModal($event)">
                                Cancel PatchDay
                            </v-btn>
                        </v-card-text>
                    </v-card>
                </v-flex>
            </v-layout>

            <patch-day-table :protocols="project.protocols"></patch-day-table>
        </v-container>
        <tech-history-modal></tech-history-modal>
        <patch-day-signup-modal></patch-day-signup-modal>
        <patch-day-cancel-modal></patch-day-cancel-modal>
    </div>
</template>

<script>
  import eventBus from 'components/event-bus'
  import filters from 'mixins/filters'
  import TechHistoryModal from 'components/modals/TechHistoryModal'
  import PatchDaySignupModal from 'components/modals/PatchDaySignupModal'
  import PatchDayCancelModal from 'components/modals/PatchDayCancelModal'
  import ProjectInfo from 'pages/project/components/ProjectInfo'
  import PatchDayTable from 'pages/project/components/PatchDayTable'

  export default {
    components: {
      TechHistoryModal,
      PatchDaySignupModal,
      PatchDayCancelModal,
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
      signupModal(event) {
        event.preventDefault()
        event.stopPropagation()

        eventBus.$emit('patch_day_signup.view.modal', this.project)
      },
      cancelModal(event) {
        event.preventDefault()
        event.stopPropagation()

        eventBus.$emit('patch_day_cancel.view.modal', this.project)
      },
      getProject() {
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
    },
    mounted() {
      this.getProject()

      eventBus.$on('patch_day.signed_up', () => {
        this.getProject()
      })

      eventBus.$on('patch_day.cancelled', () => {
        this.getProject()
      })
    }
  }
</script>