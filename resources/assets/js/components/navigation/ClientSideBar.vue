<template>
    <v-navigation-drawer
            app clipped light fixed
            v-model="sidebarOpen">
        <v-list>
            <v-list-tile v-for="(item,i) in items" :key="i"
                         :to="item.href" exact>
                <v-list-tile-title v-text="item.title"/>
            </v-list-tile>
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
