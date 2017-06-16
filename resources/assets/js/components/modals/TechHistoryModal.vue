<template>
    <v-dialog v-model="isOpen" width="640">

        <v-card>
            <v-card-row>
                <v-card-title>Technology History</v-card-title>
            </v-card-row>
            <v-card-row>
                <v-card-text>
                    <v-expansion-panel expand>
                        <v-expansion-panel-content v-for="tech, index in items"
                                                   :key="index">
                            <div slot="header">
                                <v-icon>update</v-icon>{{ tech[0].name }}
                            </div>
                            <v-list>
                                <v-list-item v-for="version in tech"
                                             :key="version.id">
                                    <v-list-tile
                                            @click.native="protocolDetail($event,
                                            version)">
                                        <v-list-tile-content>
                                            <v-list-tile-title>
                                                {{ version.version }}
                                            </v-list-tile-title>
                                        </v-list-tile-content>
                                        <v-list-tile-action
                                                v-if="version.pivot.protocol_id">
                                            <v-icon>navigate_next</v-icon>
                                        </v-list-tile-action>
                                    </v-list-tile>
                                </v-list-item>
                            </v-list>
                        </v-expansion-panel-content>
                    </v-expansion-panel>

                    <v-card-row actions>
                        <v-btn class="green--text darken-1" flat="flat">
                            Close
                        </v-btn>
                    </v-card-row>
                </v-card-text>
            </v-card-row>

        </v-card>
    </v-dialog>
</template>

<script>
  import eventBus from 'components/event-bus'
  export default {
    name: 'tech-history-modal',
    data() {
      return {
        isOpen: false,
        items: {
        }
      }
    },
    mounted () {
      eventBus.$on('tech_history.view.modal', history_items => {

        let items = {}

        history_items.forEach(item => {
          if(!items[item.name]) {
            items[item.name] = []
            items[item.name].push(item)
          } else {
            items[item.name].push(item)
          }

        })

        this.items = items
        this.isOpen = true
      })
    },
    methods: {
      protocolDetail(event, version) {
        event.preventDefault()
        event.stopPropagation()

        this.isOpen = false

        if (version.pivot.protocol_id) {
          this.$nextTick(() => {
            this.$router.push(`/protocols/${version.pivot.protocol_id}`)
          })
        } else {
          this.$router.push(`/projects/${version.pivot.project_id}`)
        }
      }
    }
  }
</script>

<style lang="scss">
</style>