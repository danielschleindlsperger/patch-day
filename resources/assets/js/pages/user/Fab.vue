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
                        class="blue darken-2"
                        dark
                        fab
                        hover
                        v-model="fab.dialOpen"
                >
                    <v-icon>keyboard_arrow_up</v-icon>
                    <v-icon>close</v-icon>
                </v-btn>

                <v-btn v-for="action in fabActions"
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

        <create-user></create-user>
        <delete-user></delete-user>
        <edit-user></edit-user>
    </div>
</template>

<script>
  import eventBus from 'components/event-bus'
  import CreateUser from 'pages/user/CreateUser'
  import DeleteUser from 'pages/user/DeleteUser'
  import EditUser from 'pages/user/EditUser'

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
    props: ['fabActions', 'user'],
    components: {
      CreateUser,
      DeleteUser,
      EditUser,
    },
    methods: {
      openModal(event, action) {
        event.preventDefault()
        event.stopPropagation()
        eventBus.$emit(action.event, this.user)
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