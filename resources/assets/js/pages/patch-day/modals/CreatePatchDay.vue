<template>
    <v-dialog persistent v-model="isOpen" width="640"
              class="create-patch-day-modal">
        <v-card>
            <v-card-title class="pa-4">
                <h2 class="title ma-0">Create PatchDay</h2>
            </v-card-title>

            <v-card-text v-if="last_patch_day">
                Previous PatchDay was on {{ last_patch_day.date | Date }}
            </v-card-text>

            <v-card-text>
                <v-menu
                        lazy
                        :close-on-content-click="true"
                        v-model="datePickerOpen"
                        transition="v-scale-transition"
                        offset-y
                        :nudge-left="56"
                >
                    <v-text-field
                            slot="activator"
                            label="Date"
                            v-model="patch_day.date"
                            prepend-icon="event"
                            required
                            readonly
                    ></v-text-field>
                    <v-date-picker v-model="patch_day.date" no-title
                                   scrollable>
                    </v-date-picker>
                </v-menu>
                <small>*indicates required field</small>
            </v-card-text>
            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn class="green--text darken-1" flat="flat"
                       @click.native="isOpen = false">Close
                </v-btn>
                <v-btn class="green--text darken-1" flat="flat"
                       @click.native="createPatchDay()">Save
                </v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<script>
  import eventBus from 'components/event-bus'
  import filters from 'mixins/filters'
  import repo from 'repository'

  export default {
    name: 'create-patch-day',
    mixins: [filters],
    data() {
      return {
        isOpen: false,
        datePickerOpen: false,
        patch_day: {
          date: '',
        },
        last_patch_day: {
          id: null,
        }
      }
    },
    mounted () {
      repo.patch_day.getAll().then((patch_days) => {
        this.last_patch_day = patch_days[0]
      })

      eventBus.$on('patch_day.create.modal', () => {
        this.isOpen = true
      })
    },
    methods: {
      createPatchDay() {
        repo.patch_day.create(this.patch_day).then(() => {
          this.isOpen = false
          this.patch_day.date = ''
        })
      },
    }
  }
</script>

<style lang="scss" scoped>
    .create-user-modal .dialog {
        overflow-y: visible;
    }

    small {
        display: block;
    }
</style>