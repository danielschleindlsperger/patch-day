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
                        class="blue darken-2"
                        dark
                        fab
                        hover
                        v-model="dialOpen"
                >
                    <v-icon>keyboard_arrow_up</v-icon>
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

        <create-patch-day></create-patch-day>
        <delete-patch-day></delete-patch-day>
        <edit-patch-day></edit-patch-day>
    </div>
</template>

<script>
  import eventBus from 'components/event-bus'
  import CreatePatchDay from 'pages/patch-day/CreatePatchDay'
  import DeletePatchDay from 'pages/patch-day/DeletePatchDay'
  import EditPatchDay from 'pages/patch-day/EditPatchDay'

  export default {
    name: 'fab',
    data() {
      return {
          dialOpen: false,
      }
    },
    props: ['fabActions', 'patch_day', 'show'],
    components: {
      CreatePatchDay,
      DeletePatchDay,
      EditPatchDay,
    },
    methods: {
      openModal(event, action) {
        event.preventDefault()
        event.stopPropagation()
        eventBus.$emit(action.event, this.patch_day)
      }
    },
  }
</script>