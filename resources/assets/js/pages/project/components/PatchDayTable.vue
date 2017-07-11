<template>
    <v-card>
        <v-card-title>
            <h3 class="text-xs-center display-1 mb-0 pa-3">PatchDays</h3>
        </v-card-title>
        <v-card-text>
            <v-data-table
                    :headers="tableHeaders"
                    :items="protocols"
                    hide-actions
            >
                <template slot="items" scope="props">
                    <td>
                        <router-link
                                :to="'/protocols/' + props.item.id">
                            {{ props.item.patch_day.name }}
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
        </v-card-text>
    </v-card>
</template>

<script>
  import filters from 'mixins/filters'

  export default {
    name: 'patch-day-table',
    props: ['protocols'],
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
        },
        tableHeaders: [
          {
            text: 'Name',
            align: 'left',
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
  }
</script>