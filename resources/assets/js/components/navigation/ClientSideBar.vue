<template>
    <v-navigation-drawer v-model="sidebarOpen"
                         :close-on-click="true"
                         persistent light clipped
                         enable-resize-watcher>
        <v-list>
            <v-list-item v-for="(item,i) in items" :key="i">
                <v-list-tile router :href="item.href">
                    <v-list-tile-title v-text="item.title"/>
                </v-list-tile>
            </v-list-item>
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
          {title: 'My Projects', href: '/projects'},
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

<style lang="scss" scoped>
    .list__tile {
        color: darken(white, 25%);
        &.list__tile--active {
            > .list__tile__title {
                font-weight: bold;
            }
        }
    }
</style>
