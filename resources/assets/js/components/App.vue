<template>
    <v-app id="sidebar-example-2" class="elevation-1" top-toolbar
           left-fixed-sidebar sidebar-under-toolbar>
        <v-toolbar class="primary">
            <v-toolbar-side-icon @click.native.stop="sidebar.open =
            !sidebar.open"/>
            <v-toolbar-title class="hidden-sm-and-down">PatchDay
            </v-toolbar-title>
        </v-toolbar>
        <main>
            <v-sidebar height="100%"
                       drawer v-model="sidebar.open"
                       :close-on-click="sidebar.closeOnClick" fixed>
                <v-list>
                    <v-list-item v-for="(item,i) in items" :key="i">
                        <v-list-tile router :href="item.href">
                            <v-list-tile-title v-text="item.title"/>
                        </v-list-tile>
                    </v-list-item>
                </v-list>
            </v-sidebar>
            <v-content>
                <v-container fluid>
                    <router-view></router-view>
                </v-container>
            </v-content>
        </main>
    </v-app>
</template>

<script>
  export default {
    data () {
      return {
        items: [
          {title: 'Dashboard', href: '/'},
        ],
        sidebar: {
          open: true,
          closeOnClick: false,
        }
      }
    },
  }
</script>

<style lang="scss">
    .toolbar {
        z-index: 10;
    }

    .content {
        padding: 1rem;
    }
</style>