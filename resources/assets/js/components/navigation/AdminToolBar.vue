<template>
    <v-toolbar class="primary" dark app clipped-left fixed>

        <v-toolbar-side-icon dark @click.stop="toggleSidebar()"/>

        <v-toolbar-title>PatchDay</v-toolbar-title>

        <v-spacer></v-spacer>

        <v-toolbar-items class="hidden-sm-and-down">
            <v-btn flat ripple to="/" exact>Dashboard</v-btn>
            <v-btn flat ripple to="/companies">Companies</v-btn>
            <v-btn flat ripple to="/projects">Projects</v-btn>
            <v-btn flat ripple to="/patch-days">Patch Days</v-btn>
            <v-btn flat ripple to="/users">Users</v-btn>
        </v-toolbar-items>

        <v-menu left bottom offset-y>
            <v-btn icon="icon" slot="activator" dark>
                <v-icon>more_vert</v-icon>
            </v-btn>
            <v-list>
                <v-list-tile>
                    <v-list-tile-title @click.stop="logout">Logout</v-list-tile-title>
                </v-list-tile>
            </v-list>
        </v-menu>
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