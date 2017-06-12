<template>
    <v-app id="patch-day" class="elevation-1">
        <side-bar :sidebar="sidebar"></side-bar>
        <tool-bar></tool-bar>
        <main>
            <v-container fluid>
                <router-view></router-view>
            </v-container>
        </main>
        <info-bar></info-bar>
    </v-app>
</template>

<script>
  import ToolBar from 'components/navigation/Toolbar'
  import SideBar from 'components/navigation/SideBar'
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
      ToolBar,
      SideBar,
      InfoBar,
    },
  }
</script>