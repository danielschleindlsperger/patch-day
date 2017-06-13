<template>
    <v-toolbar class="primary" light>
        <v-toolbar-side-icon light @click.native.stop="toggleSidebar()"/>
        <v-toolbar-title>PatchDay</v-toolbar-title>
        <v-toolbar-items>
            <v-toolbar-item class="hidden-sm-and-down"
                            ripple router href="/">
                Dashboard
            </v-toolbar-item>
            <v-toolbar-item class="hidden-sm-and-down"
                            ripple router href="/companies">
                Companies
            </v-toolbar-item>
            <v-toolbar-item class="hidden-sm-and-down"
                            ripple router href="/projects">
                Projects
            </v-toolbar-item>
            <v-toolbar-item class="hidden-sm-and-down"
                            ripple router href="/patch-days">
                Patch Days
            </v-toolbar-item>
            <v-toolbar-item class="hidden-sm-and-down"
                            ripple router href="/users">
                Users
            </v-toolbar-item>
            <v-menu left bottom offset-y>
                <v-btn icon="icon" slot="activator" light>
                    <v-icon>more_vert</v-icon>
                </v-btn>
                <v-list>
                    <v-list-item>
                        <v-list-tile>
                            <v-list-tile-title @click="logout">Logout
                            </v-list-tile-title>
                        </v-list-tile>
                    </v-list-item>
                </v-list>
            </v-menu>
        </v-toolbar-items>
    </v-toolbar>
</template>

<script>
  import eventBus from 'components/event-bus'
  export default {
    name: 'tool-bar',
    data() {
      return {}
    },
    methods: {
      logout() {
        this.$http.post('/logout')
          .then(response => {
            this.$root.user = {}
            this.$router.push('/login')
          })
          .catch(error => {
            console.error(error)
            eventBus.$emit('info.snackbar', error.response.data.error)
          })
      },
      toggleSidebar() {
        eventBus.$emit('sidebar.toggle')
      }
    },
  }
</script>