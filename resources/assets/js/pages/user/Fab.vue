<template>
    <div>
        <v-fab-transition>
            <v-speed-dial
                    bottom
                    right
                    fixed
                    transition="slide-y-reverse-transition"
                    v-model="dialOpen"
                    v-show="show"
            >

                <v-btn
                        slot="activator"
                        class="orange darken-2"
                        dark
                        fab
                        hover
                        v-model="dialOpen"
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
                       v-tooltip:left="{ html: tooltipHtml(action.tooltip), visible:
                       tooltipVisible(action.tooltip) }"
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
  import tooltip from 'mixins/tooltip'
  import CreateUser from 'pages/user/CreateUser'
  import DeleteUser from 'pages/user/DeleteUser'
  import EditUser from 'pages/user/EditUser'

  export default {
    name: 'fab',
    mixins: [tooltip],
    data() {
      return {
        dialOpen: false,
      }
    },
    props: ['fabActions', 'user', 'show'],
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
    }
  }
</script>