<template>
    <v-container>
        <v-layout>
            <v-flex xs12 lg6>
                <upcoming></upcoming>
            </v-flex>
            <v-flex xs12 lg6>
                <quick-create></quick-create>
            </v-flex>
        </v-layout>
    </v-container>
</template>

<script>
  import Upcoming from 'components/UpcomingPatchDays'
  import QuickCreate from 'components/QuickCreate'
  import eventBus from 'components/event-bus'

  export default {
    components: {
      Upcoming,
      QuickCreate,
    }
  }
</script>