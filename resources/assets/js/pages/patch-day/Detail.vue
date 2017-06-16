<template>
    <div>
        <v-container>
            <v-layout justify-center child-flex[-sm]>
                <h1 class="display-1 text-xs-center flex">
                    PatchDay #{{ patch_day.id }}</h1>
                <v-btn class="flex"
                       flat="flat" icon ripple
                       @click.native="editPatchDayModal($event)">
                    <v-icon class="grey--text">
                        mode_edit
                    </v-icon>
                </v-btn>

                <v-btn class="flex"
                       flat="flat" icon ripple
                       @click.native="deletePatchDayModal($event)">
                    <v-icon class="grey--text">
                        delete
                    </v-icon>
                </v-btn>
            </v-layout>

            <h2 class="headline text-xs-center flex">
                {{ patch_day.date | Date }}
            </h2>

            <div v-if="patch_day.protocols.length > 0">
                <v-subheader>Signed up</v-subheader>

                <v-list>
                    <v-list-item v-for="protocol in patch_day.protocols"
                                 :key="protocol.id">
                        <v-list-tile router
                                     :href="`/protocols/${protocol.id}`">
                            <v-list-tile-content>
                                <v-list-tile-title>
                                    <strong>
                                        {{ protocol.project.company.name }}
                                    </strong>
                                    / {{ protocol.project.name }}
                                </v-list-tile-title>
                            </v-list-tile-content>
                        </v-list-tile>
                    </v-list-item>
                </v-list>
            </div>
            <div v-else>
                Nobody signed up yet.
            </div>
        </v-container>

        <edit-patch-day></edit-patch-day>
        <delete-patch-day></delete-patch-day>
    </div>
</template>

<script>
  import eventBus from 'components/event-bus'
  import filters from 'mixins/filters'

  import DeletePatchDay from 'pages/patch-day/DeletePatchDay'
  import EditPatchDay from 'pages/patch-day/EditPatchDay'

  export default {
    components: {
      DeletePatchDay,
      EditPatchDay,
    },
    mixins: [filters],
    data() {
      return {
        patch_day: {
          id: null,
          date: '',
          protocols: []
        },
      }
    },
    mounted() {
      this.getPatchDay()

      eventBus.$on('patch_day.edited', () => {
        this.getPatchDay()
      })

      eventBus.$on('patch_day.deleted', patch_day => {
        // this protocol was deleted
        if (patch_day.id === this.patch_day.id) {
          this.$router.push(`/patch-days`)
        }
      })
    },
    methods: {
      getPatchDay() {
        const ID = this.$route.params.id
        this.$http.get(`/patch-days/${ID}`)
          .then(response => {
            this.patch_day = response.data
          })
          .catch(error => {
            console.error(error.response.data)
            if (error.response.status === 404) {
              this.$router.push({name: 'not-found'})
            }
          })
      },
      deletePatchDayModal(event) {
        event.preventDefault()
        event.stopPropagation()
        eventBus.$emit('patch_day.delete.modal', this.patch_day)
      },
      editPatchDayModal(event) {
        event.preventDefault()
        event.stopPropagation()
        eventBus.$emit('patch_day.edit.modal', this.patch_day)
      }
    }
  }
</script>

<style lang="scss" scoped>

</style>