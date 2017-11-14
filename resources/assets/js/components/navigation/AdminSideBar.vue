<template>
    <v-navigation-drawer
            app clipped light fixed
            v-model="sidebarOpen">
        <v-list>
            <template v-for="(item, i) in items">
                <v-list-tile :to="item.href" :key="i" exact>
                    <v-list-tile-title v-text="item.title"/>
                </v-list-tile>
            </template>
        </v-list>
    </v-navigation-drawer>

</template>

<script>
  import eventBus from 'components/event-bus'
  export default {
    name: 'side-bar',
    data () {
      return {
        sidebarOpen: false,
        items: [
          {title: 'Dashboard', href: '/'},
          {title: 'Companies', href: '/companies'},
          {title: 'Projects', href: '/projects'},
          {title: 'Patch Days', href: '/patch-days'},
          {title: 'Users', href: '/users'},
        ],
      }
    },
    mounted() {
      eventBus.$on('sidebar.toggle', () => {
        this.sidebarOpen = !this.sidebarOpen
      })
    },
  }
</script>
