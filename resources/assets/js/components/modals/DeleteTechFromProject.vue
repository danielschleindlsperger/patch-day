<template>
    <div>
        <small>
            Remove technology from project? (Updates will still appear in the projects history)
        </small>
        <v-menu>
            <v-btn slot="activator">Delete Tech</v-btn>
            <v-list>
                <v-list-tile v-for="tech in currentTechs"
                             :key="tech.id"
                             @click="promptDeletion(tech)">
                    <v-list-tile-title>{{ tech.name }}</v-list-tile-title>
                </v-list-tile>
            </v-list>
        </v-menu>

        <v-dialog v-model="promptDialog" max-width="640">
            <v-card>
                <v-card-title class="pa-4">
                    <h2 class="title ma-0">Delete {{ deleteTech.name
                        }} from {{ project.name }}?</h2>
                </v-card-title>
                <v-card-text>
                    Although the past updates are still visible this might still
                    have side effects and the action might not be reversable.
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn class="green--text darken-1" flat="flat"
                           @click.native="promptDialog = false">Don't Delete
                    </v-btn>
                    <v-btn color="red" class="darken-1" flat="flat"
                           @click.native="deleteForReal()">Delete
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>

    </div>
</template>

<script>
  import repo from 'repository'

  export default {
    name: 'delete-tech-from-project',
    props: ['project'],
    data () {
      return {
        deleteTech: {},
        promptDialog: false,
      }
    },
    computed: {
      currentTechs () {
        return this.project.current_technologies
      }
    },
    methods: {
      promptDeletion(tech) {
        this.deleteTech = tech
        this.promptDialog = true
      },
      deleteForReal() {
        repo.project.deleteTechnology(this.project, this.deleteTech).then((res) => {
          this.promptDialog = false
          this.deleteTech = {}
        })

      }
    }
  }
</script>