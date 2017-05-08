<template>
    <v-app id="patch-day" class="elevation-1" top-toolbar left-fixed-sidebar
           sidebar-under-toolbar>
        <v-toolbar class="primary">
            <v-toolbar-side-icon
                    class="hidden-lg-and-up"
                    @click.native.stop="sidebar.open = !sidebar.open"/>
            <v-toolbar-title class="hidden-sm-and-down">PatchDay
            </v-toolbar-title>
            <v-toolbar-items class="hidden-md-and-down">
                <v-toolbar-item ripple router href="/">
                    Dashboard
                </v-toolbar-item>
                <v-toolbar-item ripple router href="/companies">
                    Companies
                </v-toolbar-item>
                <v-toolbar-item ripple router href="/projects">
                    Projects
                </v-toolbar-item>
            </v-toolbar-items>
        </v-toolbar>
        <main>
            <side-bar :sidebar="sidebar"></side-bar>
            <v-content>
                <v-container fluid>
                    <router-view></router-view>
                </v-container>
            </v-content>
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
          closeOnClick: true,
          absolute: true,
//          mobile: true,
//          drawer: true,
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

    main {
        position: relative;
    }

    .content {
        padding: 1rem;
    }
</style>