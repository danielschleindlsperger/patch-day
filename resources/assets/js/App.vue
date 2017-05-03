<template>
    <v-app id="patch-day" class="elevation-1" top-toolbar
           left-fixed-sidebar sidebar-under-toolbar>
        <v-toolbar class="primary">
            <v-toolbar-side-icon @click.native.stop="sidebar.open =
            !sidebar.open"/>
            <v-toolbar-title class="hidden-sm-and-down">PatchDay
            </v-toolbar-title>
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
          open: true,
          closeOnClick: false,
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
    .toolbar {
        z-index: 10;
        position: fixed;
    }

    main {
        padding-top: 64px;
    }

    .content {
        padding: 1rem;
    }
</style>