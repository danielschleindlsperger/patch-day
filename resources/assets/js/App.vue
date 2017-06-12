<template>
    <v-app id="patch-day" class="elevation-1">
        <side-bar :sidebar="sidebar"></side-bar>
        <v-toolbar class="primary">
            <v-toolbar-side-icon class="white--text"
                    @click.native.stop="sidebar.open = !sidebar.open"/>
            <v-toolbar-title class="hidden-sm-and-down white--text">PatchDay
            </v-toolbar-title>
            <v-toolbar-items class="hidden-xs-and-down">
                <v-toolbar-item ripple router href="/">
                    Dashboard
                </v-toolbar-item>
                <v-toolbar-item ripple router href="/companies">
                    Companies
                </v-toolbar-item>
                <v-toolbar-item ripple router href="/projects">
                    Projects
                </v-toolbar-item>
                <v-toolbar-item ripple router href="/users">
                    Users
                </v-toolbar-item>
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
    }
  }
</script>

<style lang="scss">

</style>