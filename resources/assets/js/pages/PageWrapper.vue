<template>
    <main>
        <v-container fluid>
            <v-progress-circular
                    v-show="loading"
                    indeterminate
                    :size="80"
                    :width="2"
                    class="primary--text"
            ></v-progress-circular>

            <transition name="page">
                <router-view v-show="!loading"></router-view>
            </transition>

        </v-container>
    </main>
</template>

<script>
  import eventBus from 'components/event-bus'

  export default {
    name: 'page-wrapper',
    data() {
      return {
        loading: true,
      }
    },
    beforeCreate() {
      eventBus.$on('page.loading', (loading) => {
        this.loading = loading
      })
    }
  }
</script>

<style lang="scss" scoped>
    .progress-circular {
        position: fixed;
        top: 30%;
        left: 50%;
        transform: translate3d(-50%, -50%, 0);
    }
    .page-enter-active, .page-enter-leave-active {
        transition: opacity .3s
    }

    .page-enter, .page-leave-to {
        opacity: 0
    }
</style>
