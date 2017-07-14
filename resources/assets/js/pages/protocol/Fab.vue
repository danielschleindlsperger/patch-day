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
                       @click.native="openModal($event, action)"
                >
                    <v-icon>{{ action.icon }}</v-icon>
                </v-btn>
            </v-speed-dial>
        </v-fab-transition>

        <edit-protocol></edit-protocol>
    </div>
</template>

<script>
  import eventBus from 'components/event-bus'
  import EditProtocol from 'pages/protocol/modals/EditProtocol'

  export default {
    name: 'fab',
    data() {
      return {
        dialOpen: false,
      }
    },
    props: ['fabActions', 'protocol', 'show'],
    components: {
      EditProtocol,
    },
    methods: {
      openModal(event, action) {
        event.preventDefault()
        event.stopPropagation()
        eventBus.$emit(action.event, this.protocol)
      }
    },
  }
</script>