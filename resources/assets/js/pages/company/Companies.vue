<template>
    <v-card>
        <v-card-text>
            <div class="button-row">
                <v-btn primary dark>
                    <v-icon class="white--text text--darken-2">add_circle
                    </v-icon>
                </v-btn>
            </div>
            <v-list>
                <v-list-item v-for="item in list" :key="item.id">
                    <v-list-tile avatar router :href="item.href">
                        <v-list-tile-avatar>
                            <v-icon>business</v-icon>
                        </v-list-tile-avatar>
                        <v-list-tile-content>
                            <v-list-tile-title>{{ item.name }}
                            </v-list-tile-title>
                        </v-list-tile-content>
                        <v-list-tile-action>
                            <v-btn icon ripple>
                                <v-icon class="grey--text text--lighten-1">
                                    info
                                </v-icon>
                            </v-btn>
                        </v-list-tile-action>
                    </v-list-tile>
                </v-list-item>
            </v-list>
        </v-card-text>
    </v-card>
</template>

<script>
  export default {
    data() {
      return {
        list: []
      }
    },
    mounted() {
      this.$http.get('/companies')
        .then(response => {
          this.list = response.data
          this.addLinks()
          console.log(response.data)
        })
        .catch(error => {
          error.response.data
        })
    },
    methods: {
      addLinks() {
        this.list.forEach(item => {
          item.href = `/companies/${item.id}`
        })
      }
    }
  }
</script>

<style lang="scss" scoped>
    .button-row {
        display: flex;
        flex-flow: row nowrap;
        justify-content: flex-end;
    }
</style>