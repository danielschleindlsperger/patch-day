<template>
    <v-app id="patch-day">
        <side-bar></side-bar>
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
  import ToolBar from 'components/navigation/AdminToolBar'
  import SideBar from 'components/navigation/AdminSideBar'
  import InfoBar from 'components/InfoBar'

  export default {
    data() {
      return {}
    },
    components: {
      ToolBar,
      SideBar,
      InfoBar,
    },
  }
</script>