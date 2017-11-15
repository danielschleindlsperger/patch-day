<template>
    <div>
        <v-container>

            <h1 class="display-1 text-xs-center mb-4">
                {{ project.name }}
            </h1>

            <v-layout mb-5>

                <v-flex xs12 lg6>
                    <project-info :project="project"></project-info>
                </v-flex>

                <v-flex xs12 lg6>
                    <v-card>

                        <v-card-title primary-title class="primary pa-4">
                            <h2 class="title white--text ma-0">
                                Project Actions</h2>
                        </v-card-title>

                        <v-card-text>

                            <v-btn color="primary"
                                   @click.native="signupModal($event)">
                                Sign up for PatchDay
                            </v-btn>

                            <v-btn class="primary"
                                   @click.native="cancelModal($event)">
                                Cancel PatchDay
                            </v-btn>

                        </v-card-text>
                    </v-card>
                </v-flex>
            </v-layout>

            <patch-day-table v-if="project.protocols.length"
                             :protocols="project.protocols" />
        </v-container>
        <tech-history-modal></tech-history-modal>
        <patch-day-signup-modal></patch-day-signup-modal>
        <patch-day-cancel-modal></patch-day-cancel-modal>
    </div>
</template>

<script>
  import eventBus from 'components/event-bus'
  import filters from 'mixins/filters'
  import repo from 'repository'
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
    beforeRouteEnter (to, from, next) {
      repo.project.get(to.params.id).then((project) => {
        next((vm) => {
          vm.project = project
        })
      })
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
        repo.project.get(this.project.id).then((project) => {
          this.project = project
        })
      }
    },
    mounted() {
      eventBus.$on('patch_day.signed_up', () => {
        this.getProject()
      }).$on('patch_day.cancelled', () => {
        this.getProject()
      })
    }
  }
</script>