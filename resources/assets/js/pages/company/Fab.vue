<template>
    <div>
        <v-fab-transition>
            <v-speed-dial
                    bottom
                    right
                    fixed
                    transition="slide-y-reverse-transition"
                    v-model="fab.dialOpen"
                    v-show="!fab.hidden"
            >

                <v-btn
                        slot="activator"
                        class="orange darken-2"
                        dark
                        fab
                        hover
                        v-model="fab.dialOpen"
                >
                    <v-icon>menu</v-icon>
                    <v-icon>close</v-icon>
                </v-btn>

                <v-btn v-for="action in fabActions"
                       :key="action.icon"
                       fab
                       dark
                       small
                       :class="action.color"
                       @click.native="openModal($event, action)"
                >
                    <v-icon>{{ action.icon }}</v-icon>
                </v-btn>
            </v-speed-dial>
        </v-fab-transition>
        <delete-company></delete-company>
        <edit-company></edit-company>
        <create-company></create-company>
    </div>
</template>

<script>
  import eventBus from 'components/event-bus'
  import DeleteCompany from 'pages/company/DeleteCompany'
  import EditCompany from 'pages/company/EditCompany'
  import CreateCompany from 'pages/company/CreateCompany'

  export default {
    name: 'fab',
    data() {
      return {
        fab: {
          hidden: true,
          dialOpen: false,
        },
      }
    },
    props: ['fabActions', 'company'],
    components: {
      DeleteCompany,
      EditCompany,
      CreateCompany,
    },
    methods: {
      openModal(event, action) {
        event.preventDefault()
        event.stopPropagation()
        eventBus.$emit(action.event, this.company)
      }
    },
    mounted() {
      eventBus.$on('page.loading', (loading) => {
        if (!loading) {
          this.fab.hidden = false
        }
      })
    },
  }
</script>