<template>
    <v-app id="patch-day">
        <side-bar></side-bar>
        <tool-bar></tool-bar>
        <page-wrapper></page-wrapper>
        <info-bar></info-bar>
    </v-app>
</template>

<script>
  import PageWrapper from 'pages/PageWrapper'
  import ToolBar from 'components/navigation/ClientToolBar'
  import SideBar from 'components/navigation/ClientSideBar'
  import InfoBar from 'components/InfoBar'

  export default {
    data() {
      return {}
    },
    components: {
      PageWrapper,
      ToolBar,
      SideBar,
      InfoBar,
    },
  }
</script>