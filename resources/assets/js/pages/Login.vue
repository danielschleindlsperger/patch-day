<template>
    <v-container>
        <v-row>
            <v-col xs12 lg8 xl6 class="card-wrapper">
                <v-card>
                    <v-card-row>
                        <v-card-title>
                            <h1>Login</h1>
                        </v-card-title>
                    </v-card-row>
                    <v-card-text>
                        <v-text-field name="email" label="Email"
                                      prepend-icon="email"
                                      v-model="email"
                                      :rules="rules"
                                      @keyup.enter.native="login"></v-text-field>

                        <v-text-field type="password" name="password"
                                      label="Password"
                                      prepend-icon="security"
                                      v-model="password"
                                      :rules="rules"
                                      @keyup.enter.native="login"></v-text-field>
                        <div class="button-row">
                            <v-btn default primary dark class="white--text"
                                   @click.native="login">
                                Login
                            </v-btn>
                        </div>
                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>
    </v-container>
</template>

<script>
  import eventBus from 'components/event-bus'
  export default {
    data() {
      return {
        email: '',
        password: '',
        error: '',
        rules: [
          () => {
            if (this.error === '') {
              return true;
            } else {
              return this.error;
            }
          }
        ]
      }
    },
    methods: {
      login() {
        this.$http.post('/login', {
          email: this.email,
          password: this.password,
        })
          .then((response) => {
            if (response.data.success === true) {
              eventBus.$emit('info.snackbar', 'Login successful!')
              this.$router.push('/')
            }
          })
          .catch((error) => {
            this.error = error.response.data.error
            this.validateAllElements()
          })
      },
      validateAllElements () {
        const children = this.$children
        if (!children.length) {
          return
        }
        children.forEach(child => {
          if (child && typeof(child.validate) === 'function') {
            child.validate()
          }
        })
      }
    }
  }
</script>

<style lang="scss" scoped>
    .card-wrapper {
        margin: auto;
    }

    .card {
        margin-top: 2rem;
    }

    h1 {
        font-size: 48px;
        margin: auto;
    }

    .button-row {
        display: flex;
        flex-flow: row nowrap;
        justify-content: flex-end;
    }

</style>