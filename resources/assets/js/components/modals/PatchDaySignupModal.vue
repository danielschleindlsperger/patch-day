<template>
    <v-dialog v-model="isOpen" width="640">

        <v-card>
            <v-card-title class="pa-4">
                <h2 class="title ma-0">Sign up for PatchDay</h2>
            </v-card-title>

            <v-card-text>
                <v-select
                        :items="patch_days"
                        v-model="patch_day"
                        item-value="id"
                        item-text="name"
                        label="Select PatchDay"
                        return-object
                        light
                        single-line
                        auto
                >
                </v-select>

                <v-card-actions>
                    <v-spacer></v-spacer>

                    <v-btn class="green--text darken-1" flat="flat"
                           @click.native="isOpen = false">
                        Close
                    </v-btn>

                    <v-btn class="green--text darken-1" flat="flat"
                           @click.native="signUp($event)">
                        Sign Up
                    </v-btn>
                </v-card-actions>
            </v-card-text>

        </v-card>
    </v-dialog>
</template>

<script>
  import eventBus from 'components/event-bus'
  import filters from 'mixins/filters'
  import repo from 'repository'

  export default {
    name: 'patch-day-signup-modal',
    mixins: [filters],
    data() {
      return {
        isOpen: false,
        project: {},
        patch_day: {},
        patch_days: [],
      }
    },
    mounted () {
      eventBus.$on('patch_day_signup.view.modal', (project) => {
        this.project = project
        repo.project.getPossibleSignups(this.project).then((patch_days) => {
          this.patch_days = patch_days
        })
        this.isOpen = true
      })
    },
    methods: {
      signUp() {
        repo.project.signUpForPatchDay(this.project, this.patch_day)
          .then(() => {
            this.isOpen = false
          })
      }
    }
  }
</script>