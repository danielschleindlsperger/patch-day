<template>
    <v-dialog persistent v-model="isOpen" width="640"
              class="create-user-modal">
        <v-card>
            <v-card-title class="pa-4">
                <h2 class="title ma-0">Create User</h2>
            </v-card-title>

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
            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn class="green--text darken-1" flat="flat"
                       @click.native="isOpen = false">Close
                </v-btn>
                <v-btn class="green--text darken-1" flat="flat"
                       @click.native="createUser()">Save
                </v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<script>
  import eventBus from 'components/event-bus'
  import repo from 'repository'

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
              return Number.isInteger(this.user.company_id) || 'Please select an entry'
            },
          ]
        }
      }
    },
    mounted () {
      repo.company.getAll().then((companies) => {
        this.companies = companies
      })

      eventBus.$on('user.create.modal', () => {
        this.isOpen = true
      })
    },
    methods: {
      createUser() {
        repo.user.create(this.user).then(() => {
          this.isOpen = false
          this.user.name = ''
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