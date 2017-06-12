<template>
    <v-dialog persistent v-model="isOpen" width="640"
              class="create-user-modal">
        <v-card>
            <v-card-row>
                <v-card-title>Edit User</v-card-title>
            </v-card-row>

            <v-card-row>
                <v-card-text>
                    <v-text-field label="Name" required
                                  v-model="user.name"/>
                    <v-text-field label="Password" required type="password"
                                  v-model="user.password"/>
                    <v-text-field label="E-Mail" required
                                  v-model="user.email"/>
                    <v-select
                            :items="roles"
                            item-text="name"
                            item-value="name"
                            v-model="user.role"
                            label="Role"
                            required auto
                            max-height="100"
                            default=""
                    />
                    <v-select
                            :items="companies"
                            item-text="name"
                            item-value="id"
                            v-model="user.company_id"
                            label="Company"
                            required auto
                            max-height="320"
                            :rules="rules.company"
                    />
                    <small>*indicates required field</small>
                </v-card-text>
            </v-card-row>
            <v-card-row actions>
                <v-btn class="green--text darken-1" flat="flat"
                       @click.native="isOpen = false">Close
                </v-btn>
                <v-btn class="green--text darken-1" flat="flat"
                       @click.native="createUser()">Save
                </v-btn>
            </v-card-row>
        </v-card>
    </v-dialog>
</template>

<script>
  import eventBus from 'components/event-bus'
  export default {
    name: 'create-user',
    data() {
      return {
        isOpen: false,
        user: {
          name: '',
          email: '',
          password: '',
          role: 'client',
          company_id: null,
        },
        companies: [],
        roles: [
          {
            name: 'client',
          },
          {
            name: 'admin',
          },
        ],
        rules: {
          company: [
            () => {
              return Number.isInteger(this.user.company_id)|| 'Please select an entry'
            },
          ]
        }
      }
    },
    mounted () {
      this.getCompanies()

      eventBus.$on('user.create.modal', () => {
        this.isOpen = true
      })
    },
    methods: {
      createUser() {
        this.$http.post('/users', this.user)
          .then(response => {
            if (response.status === 200) {
              eventBus.$emit('user.created')
              eventBus.$emit('info.snackbar',
                `User ${this.user.name} created successfully!`)
              this.isOpen = false
              this.user.name = ''
            }
          })
          .catch(error => {
            console.error(error)
            eventBus.$emit('info.snackbar', error.response.data)
          })
      },
      getCompanies() {
        this.$http.get('/companies')
          .then(response => {
            this.companies = response.data
          })
          .catch(error => {
            console.error(error)
          })
      },
    }
  }
</script>

<style lang="scss">
    .create-user-modal .dialog {
        overflow-y: visible;
    }
</style>