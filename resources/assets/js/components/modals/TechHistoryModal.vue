<template>
    <v-dialog v-model="isOpen" max-width="640">

        <v-card>
            <v-card-title class="pa-4">
                <h2 class="title ma-0">Technology History</h2>
            </v-card-title>
            <v-card-text>
                <v-expansion-panel expand class="mb-4">
                    <v-expansion-panel-content v-for="tech, index in items"
                                               :key="index">
                        <div slot="header">
                            <v-icon>update</v-icon>
                            {{ tech[0].name }}
                        </div>
                        <v-list>
                            <v-list-tile v-for="version in tech"
                                         :key="version.id"
                                         @click.native="protocolDetail($event,
                                            version)"
                                         class="protocol-link">
                                <v-list-tile-content>
                                    <v-list-tile-title>
                                        <span>{{ version.version }}</span>
                                        <small v-if="isUpdate(version)" class="update-date">
                                            {{ version.pivot.updated_at | Date }}
                                            ({{ version.pivot.updated_at | HumanizeDate }})
                                        </small>
                                    </v-list-tile-title>
                                </v-list-tile-content>
                                <v-list-tile-action
                                        v-if="version.pivot.protocol_id">
                                    <v-icon>navigate_next</v-icon>
                                </v-list-tile-action>
                            </v-list-tile>

                        </v-list>
                    </v-expansion-panel-content>
                </v-expansion-panel>

                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn class="green--text darken-1" flat="flat"
                           @click.native="isOpen = false">
                        Close
                    </v-btn>
                </v-card-actions>
            </v-card-text>

        </v-card>
    </v-dialog>
</template>

<script>
  import eventBus from 'components/event-bus'
  import filters from 'mixins/filters'

  export default {
    name: 'tech-history-modal',
    mixins: [filters],
    data() {
      return {
        isOpen: false,
        items: {}
      }
    },
    mounted () {
      eventBus.$on('tech_history.view.modal', history_items => {

        let items = {}

        history_items.forEach(item => {
          if (!items[item.name]) {
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
      },
      isUpdate(version) {
        return version.pivot.action === 'update'
      }
    }
  }
</script>

<style lang="scss" scoped>
    .protocol-link {
        &:hover:not(:last-child) {
            cursor: pointer;
            background: darken(white, 10%);
        }
        .update-date {
            margin-left: 2em;
            color: lighten(black, 35%);
        }
    }

</style>