<template>
    <v-container>
        <h1 class="display-2 text-xs-center">TODO</h1>
        <h2 class="display-1 text-xs-center">{{ patch_day.name }}</h2>

        <h2 class="headline text-xs-center">
            Status: {{ patch_day.status }}
        </h2>

        <div v-if="patch_day.protocols.length > 0">
            <v-card>
                <v-list>
                    <v-list-tile v-for="protocol in patch_day.protocols"
                                 :key="protocol.id"
                                 :to="`/protocols/${protocol.id}`">
                        <v-list-tile-content>
                            <v-list-tile-title>
                                {{ protocol.project.company.name }}
                                /
                                <strong>{{ protocol.project.name }}</strong>
                            </v-list-tile-title>
                        </v-list-tile-content>
                    </v-list-tile>
                </v-list>
            </v-card>
        </div>
        <div v-else>
            All done!
        </div>
    </v-container>
</template>


<script>
  import repo from 'repository'

  export default {
    data() {
      return {
        patch_day: {
          id: null,
          date: '',
          protocols: []
        },
      }
    },
    beforeRouteEnter (to, from, next) {
      repo.patch_day.getTodo(to.params.id)
        .then((data) => {
          next((vm) => {
            vm.patch_day = data
          })
        })
    },
    mounted() {

    },
  }
</script>