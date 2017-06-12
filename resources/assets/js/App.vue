<template>
    <v-app id="patch-day" class="elevation-1">
        <side-bar :sidebar="sidebar"></side-bar>
        <v-toolbar class="primary" light>
            <v-toolbar-side-icon light
                    @click.native.stop="sidebar.open = !sidebar.open"/>
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
        <main>
            <v-container fluid>
                <router-view></router-view>
            </v-container>
        </main>
        <info-bar></info-bar>
    </v-app>
</template>

<script>
  import SideBar from 'components/sidebar/SideBar'
  import InfoBar from 'components/InfoBar'

  export default {
    data() {
      return {
        sidebar: {
          open: false,
        }
      }
    },
    components: {
      SideBar,
      InfoBar,
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
      }
    },
  }
</script>

<style lang="scss">

</style>