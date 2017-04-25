<template>
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
</template>

<script>
  export default {
    name: 'side-bar',
    data () {
      return {
        items: [
          {title: 'Dashboard', href: '/'},
          {title: 'Companies', href: '/companies'},
        ],
      }
    },
    props: ['sidebar']
  }
</script>