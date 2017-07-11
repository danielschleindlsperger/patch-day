<template>
    <v-dialog persistent v-model="isOpen" width="640"
              class="edit-patch-day-modal">
        <v-card>

            <v-card-title class="pa-4">
                <h2 class="title ma-0">Edit PatchDay</h2>
            </v-card-title>


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
                <v-select
                        :items="status"
                        v-model="patch_day.status"
                        item-text="name"
                        item-value="value"
                        label="PatchDay status"
                        required auto
                        max-height="320"
                        light
                />
                <small>*indicates required field</small>
            </v-card-text>

            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn class="green--text darken-1" flat="flat"
                       @click.native="isOpen = false">Close
                </v-btn>

                <v-btn class="green--text darken-1" flat="flat"
                       @click.native="editPatchDay()">Save
                </v-btn>

            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<script>
  import eventBus from 'components/event-bus'
  import filters from 'mixins/filters'

  export default {
    name: 'edit-patch-day',
    mixins: [filters],
    data() {
      return {
        isOpen: false,
        datePickerOpen: false,
        status: [
          {
            value: 'upcoming',
            name: 'Upcoming',
          },
          {
            value: 'in_progress',
            name: 'In progress',
          },
          {
            value: 'done',
            name: 'Done',
          },
        ],
        patch_day: {
          date: '',
          status: 'upcoming',
        },
        last_patch_day: {
          id: null,
        }
      }
    },
    mounted () {
      this.getPatchDays()

      eventBus.$on('patch_day.edit.modal', patch_day => {
        this.patch_day = patch_day
        this.isOpen = true
      })
    },
    methods: {
      getPatchDays() {
        this.$http.get('/patch-days')
          .then(response => {
            this.last_patch_day = response.data[0]
          })
          .catch(error => {
            console.error(error)
            eventBus.$emit('info.snackbar', error.response.data.error)
          })
      },
      editPatchDay() {
        this.$http.put(`/patch-days/${this.patch_day.id}`, this.patch_day)
          .then(response => {
            if (response.status === 200) {
              eventBus.$emit('patch_day.edited')
              eventBus.$emit('info.snackbar', `PatchDay edited successfully!`)
              this.isOpen = false
              this.patch_day.date = ''
            }
          })
          .catch(error => {
            console.error(error)
            eventBus.$emit('info.snackbar', error.response.data)
          })
      },
    }
  }
</script>

<style lang="scss" scoped>
    .edit-user-modal .dialog {
        overflow-y: visible;
    }

    small {
        display: block;
    }
</style>