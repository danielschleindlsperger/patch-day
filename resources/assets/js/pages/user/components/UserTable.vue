<template>
    <div>
        <div class="action-wrapper mb-3">
            <v-text-field
                    append-icon="search"
                    label="Search"
                    single-line
                    hide-details
                    v-model="search"
            ></v-text-field>
        </div>
        <v-card>
            <v-data-table
                    :headers="tableHeaders"
                    :items="users"
                    :search="search"
                    hide-actions
                    class="elevation-1"
            >
                <template slot="items" slot-scope="props">
                    <td>
                        <router-link :to="'/users/' + props.item.id">
                            {{ props.item.name }}
                        </router-link>
                    </td>
                    <td>
                        <router-link v-if="props.item.company"
                                :to="'/companies/' + props.item.company.id">
                            {{ props.item.company.name }}
                        </router-link>
                    </td>
                    <td>
                        {{ props.item.role }}
                    </td>
                    <td class="text-xs-right">
                        {{ props.item.created_at | DateTime }}
                    </td>
                </template>
            </v-data-table>
        </v-card>
    </div>
</template>

<script>
  import filters from 'mixins/filters'

  export default {
    name: 'user-table',
    props: ['users'],
    data() {
      return {
        search: '',
        tableHeaders: [
          {
            text: 'Name',
            align: 'left',
            value: 'name',
          },
          {
            text: 'Company',
            value: 'company.name',
            sortable: true,
            align: 'left',
          },
          {
            text: 'Role',
            value: 'role',
            sortable: true,
            align: 'left',
          },
          {
            text: 'Created',
            value: 'id',
            sortable: true,
          },
        ]
      }
    },
    mixins: [filters],
  }
</script>

<style lang="scss" scoped>
    .action-wrapper {
        display: flex;
        justify-content: flex-end;
        align-items: center;
        > div {
            width: 100%;
            max-width: 200px;
        }
    }
</style>