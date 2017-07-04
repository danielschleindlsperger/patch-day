<template>
    <div class="info-wrapper">
        <div class="headline">
            Price/PatchDay:
            {{ project.base_price | currency('EUR', true) }}
        </div>
        <div class="headline mb-4">
            Penalty:
            {{ project.penalty | currency('EUR', true) }}
        </div>

        <div class="mb-5">
            <h3 class="headline mb-0">Technologies</h3>
            <v-chip class="text-xs-center"
                    v-for="technology in project.current_technologies"
                    :key="technology.id">
                {{ technology.name }}&nbsp;{{ technology.version }}
            </v-chip>
            <div>
                <v-btn dark default
                       @click.native="techHistoryModal($event)">
                    History
                </v-btn>
            </div>
        </div>
    </div>
</template>

<script>
  import eventBus from 'components/event-bus'
  import filters from 'mixins/filters'

  export default {
    name: 'project-info',
    props: ['project'],
    mixins: [filters],
    methods: {
      techHistoryModal(event) {
        event.preventDefault()
        event.stopPropagation()
        eventBus.$emit('tech_history.view.modal', this.project.technology_history)
      },
    },
  }
</script>