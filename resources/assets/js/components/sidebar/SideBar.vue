<template>
    <v-navigation-drawer v-model="sidebar.open"
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
  export default {
    name: 'side-bar',
    data () {
      return {
        items: [
          {title: 'Dashboard', href: '/'},
          {title: 'Companies', href: '/companies'},
          {title: 'Projects', href: '/projects'},
          {title: 'Users', href: '/users'},
        ],
      }
    },
    props: ['sidebar']
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
