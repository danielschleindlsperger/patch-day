<template>
    <div>
        <v-container>
            <v-layout justify-center child-flex[-sm]>
                <h1 class="display-1 text-xs-center flex">
                    PatchDay #{{ patch_day.id }}
                </h1>
            </v-layout>

            <h2 class="headline text-xs-center">
                Protocol for
                <router-link class="uppercase"
                             :to="`/projects/${project.id}`">
                    {{ project.name }}
                </router-link>
            </h2>

            <div class="info-item">
                Done:
                <v-icon>
                    {{ protocol.done | checkIcon }}
                </v-icon>
            </div>
            <div class="info-item">
                Price: {{ protocol.price | currency('EUR', true) }}
            </div>
            <div class="info-item">
                Date: {{ protocol.date | Date }}
            </div>
            <div v-if="protocol.comment" class="info-item">
                <h3 class="subheading mb-0">Comment:</h3>
                <div class="body-2">
                    {{ protocol.comment }}
                </div>
            </div>
            <div class="info-item">
                <h3 class="subheading mb-0">Version updates:</h3>
                <div v-for="update in protocol.technology_updates">
                    <span>{{ update.name }}</span>
                    <v-icon>trending_flat</v-icon>
                    <span>{{ update.version }}</span>
                </div>
            </div>
        </v-container>
    </div>
</template>

<script>
  import eventBus from 'components/event-bus'
  import filters from 'mixins/filters'
  import repo from 'repository'

  export default {
    mixins: [filters],
    data() {
      return {
        protocol: {
          done: false,
          comment: '',
          date: '',
          patch_day: {
            id: null,
          },
          project: {
            name: '',
            current_technologies: [],
            technology_history: [],
          }
        }
      }
    },
    beforeRouteEnter (to, from, next) {
      repo.protocol.get(to.params.id).then((protocol) => {
        next((vm) => {
          vm.protocol = protocol
        })
      })
    },
    computed: {
      patch_day() {
        return this.protocol.patch_day
      },
      project() {
        return this.protocol.project
      },
    }
  }
</script>

<style lang="scss" scoped>
    .uppercase {
        text-transform: uppercase;
    }

    .info-item {
        margin-bottom: 1em;
    }
</style>