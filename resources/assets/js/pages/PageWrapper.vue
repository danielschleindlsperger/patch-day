<template>
    <main>
        <v-container fluid class="page-wrapper">
            <transition name="page">
                <v-progress-circular
                        v-show="loading"
                        indeterminate
                        :size="80"
                        :width="2"
                        class="primary--text"
                ></v-progress-circular>
            </transition>

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
        loading: false,
      }
    },
    created() {
      this.$router.beforeEach((to, from, next) => {
        this.loading = true
        next()
      })
      this.$router.afterEach((to, from) => {
        this.loading = false
      })
    }
  }
</script>

<style lang="scss" scoped>
    .page-wrapper {
        position: relative;
    }
    .progress-circular {
        position: absolute;
        top: 30vh;
        left: 50%;
        transform: translate3d(-50%, -50%, 0);
        z-index: 10;
    }
    .page-enter-active, .page-enter-leave-active {
        transition: opacity 1s ease
    }

    .page-enter, .page-leave-to {
        opacity: 0
    }
</style>
