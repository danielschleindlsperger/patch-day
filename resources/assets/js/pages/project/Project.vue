<template>
    <div>
        <v-container>
            <div class="card-head">
                <h2 class="text-xs-center">{{ project.name }}</h2>
                <div class="info-wrapper">
                    <h3>{{ project.company.name }}</h3>
                    <div class="button-row">
                        <v-btn flat="flat" icon ripple
                               @click.native="editProjectModal($event)">
                            <v-icon class="grey--text">
                                mode_edit
                            </v-icon>
                        </v-btn>
                        <v-btn flat="flat" icon ripple
                               @click.native="deleteProject($event)">
                            <v-icon class="grey--text">
                                delete
                            </v-icon>
                        </v-btn>
                    </div>
                </div>
                <v-layout class="info-wrapper">
                    <v-flex xs12 md6 class="info-item">
                        <v-icon large
                                class="grey--text text--darken-2 pr-3"
                        >
                            attach_money
                        </v-icon>
                        <h6 class="ma-0">
                            Price/PatchDay: {{ project.base_price |
                        currency('EUR', true) }}
                        </h6>
                    </v-flex>
                    <v-flex xs12 md6 class="info-item">
                        <v-icon large
                                class="grey--text text--darken-2 pr-3"
                        >
                            attach_money
                        </v-icon>
                        <h6 class="ma-0">
                            Penalty: {{ project.penalty |
                        currency('EUR', true) }}
                        </h6>
                    </v-flex>
                </v-layout>
            </div>
            <hr>
            <div class="projects">
                <h3 class="text-xs-center">PatchDays</h3>
                <v-data-table
                        :headers="tableHeaders"
                        :items="project.protocols"
                        hide-actions
                        class="elevation-1"
                >
                    <template slot="items" scope="props">
                        <td>
                            <router-link :to="'/protocols/' + props.item.id">
                                PatchDay
                                #{{ props.item.patch_day_id }}
                            </router-link>
                        </td>
                        <td class="text-xs-right">
                            {{ props.item.date | Date }}
                        </td>
                        <td class="text-xs-right">
                            <v-icon>
                                {{ props.item.done | checkIcon }}
                            </v-icon>
                        </td>
                    </template>
                </v-data-table>
            </div>
        </v-container>
        <delete-project></delete-project>
        <edit-project></edit-project>
    </div>
</template>

<script>
  import eventBus from 'components/event-bus'
  import filters from 'mixins/filters'
  import DeleteProject from 'pages/project/DeleteProject'
  import EditProject from 'pages/project/EditProject'

  export default {
    components: {
      DeleteProject,
      EditProject,
    },
    mixins: [filters],
    data() {
      return {
        project: {
          base_price: 0,
          penalty: 0,
          name: '',
          company: {
            name: ''
          },
          protocols: []
        },
        tableHeaders: [
          {
            text: 'Name',
            left: true,
            value: 'name',
          },
          {
            text: 'Date',
            value: 'date',
            sortable: true,
          },
          {
            text: 'Done',
            value: 'done',
            sortable: true,
          },
        ]
      }
    },
    methods: {
      deleteProject(event) {
        event.preventDefault()
        event.stopPropagation()
        eventBus.$emit('project.delete.modal', this.project);
      },
      editProjectModal(event) {
        event.preventDefault()
        event.stopPropagation()
        eventBus.$emit('project.edit.modal', this.project);
      },
    },
    mounted() {
      eventBus.$on('project.deleted', item => {
        // this project was deleted
        if (item.id === this.project.id) {
          this.$router.push('/projects')
        }
      })

      eventBus.$on('project.edited', project => {
        this.project = JSON.parse(JSON.stringify(project))
      })

      const projectId = this.$route.params.id
      this.$http.get(`/projects/${projectId}`)
        .then(response => {
          this.project = response.data
          console.log(response.data)
        })
        .catch(error => {
          console.error(error)
          eventBus.$emit('info.snackbar', error.response.data.error)
          if (error.response.status === 404) {
            this.$router.push({name: 'not-found'})
          }
        })
    }
  }
</script>

<style lang="scss" scoped>
    h1 {
        font-size: 48px;
        text-align: center;
    }

    .info-wrapper {
        width: 100%;
        display: flex;
        flex-flow: row wrap;
        justify-content: space-between;
        h3 {
            font-size: 32px;
        }
        .button-row {
            display: flex;
            flex-flow: row nowrap;
            justify-content: flex-end;
        }
        .info-item {
            display: flex;
            align-items: center;
            justify-content: center;
            h6 {
                display: inline-block;
            }
        }
    }

    hr {
        margin: 1rem 0 3rem;
    }
</style>