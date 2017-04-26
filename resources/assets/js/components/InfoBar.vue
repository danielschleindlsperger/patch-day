<template>
    <v-snackbar
            :timeout="timeout"
            :top="true"
            v-model="visible"
    >
        {{ message }}
        <v-btn flat class="white--text" @click.native="visible = false">Close
        </v-btn>
    </v-snackbar>
</template>

<script>
    import eventBus from 'components/event-bus'
    export default {
      data() {
        return {
          visible: false,
          timeout: 5000,
          message: '',
        }
      },
      mounted() {
        eventBus.$on('info.snackbar', message => {
          this.visible = true
          this.message = message
        })
      }
    }
</script>