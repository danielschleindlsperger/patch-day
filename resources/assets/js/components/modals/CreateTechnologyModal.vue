<template>
    <v-dialog persistent v-model="isOpen" max-width="640">
        <v-card>
            <v-card-title class="pa-4">
                <h2 class="title ma-0">Create Technology</h2>
            </v-card-title>^

            <v-card-text>
                <v-text-field label="Name" required
                              v-model="technology.name"/>
                <v-text-field label="version" required
                              v-model="technology.version"/>
                <small>*indicates required field</small>
            </v-card-text>
            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn class="green--text darken-1" flat="flat"
                       @click.native="closeModal($event)">Close
                </v-btn>
                <v-btn class="green--text darken-1" flat="flat"
                       @click.native="createTech()">Save
                </v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<script>
  import eventBus from 'components/event-bus'
  import repo from 'repository'

  export default {
    name: 'create-technology',
    props: ['event'],
    data() {
      return {
        isOpen: false,
        technology: {
          name: '',
          version: '',
        },
      }
    },
    mounted () {
      eventBus.$on('technology.create.modal', () => {
        this.isOpen = true
      })
    },
    methods: {
      createTech() {
        repo.technology.create(this.technology).then(() => {
          this.isOpen = false
          eventBus.$emit(this.event)
        })
      },
      closeModal(event) {
        event.preventDefault()
        event.stopPropagation()
        this.isOpen = false
        eventBus.$emit(this.event)
      }
    }
  }
</script>