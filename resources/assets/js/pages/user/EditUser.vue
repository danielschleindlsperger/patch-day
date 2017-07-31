<template>
    <v-dialog persistent v-model="isOpen" width="640"
              class="edit-user-modal">
        <v-card>

            <v-card-title class="pa-4">
                <h2 class="title ma-0">Edit User</h2>
            </v-card-title>

            <v-card-text>
                <v-text-field label="Name" required
                              v-model="user.name"/>
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
                       @click.native="editUser()">Save
                </v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<script>
  import eventBus from 'components/event-bus'
  import repo from 'repository'

  export default {
    name: 'edit-user',
    data() {
      return {
        isOpen: false,
        user: {
          name: '',
          email: '',
          company_id: null,
          company: {},
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
              return this.user.company &&
                Number.isInteger(this.user.company_id)
                || 'Please select an entry'
            },
          ]
        }
      }
    },
    mounted () {
      repo.company.getAll().then((companies) => {
        this.companies = companies
      })
      eventBus.$on('user.edit.modal', (user) => {
        this.user = JSON.parse(JSON.stringify(user))
        this.isOpen = true
      })
    },
    methods: {
      editUser() {
        repo.user.edit(this.user.id, this.user).then(() => {
          this.isOpen = false
        })
      },
    },
  }
</script>

<style lang="scss">
    .edit-user-modal .dialog {
        overflow-y: visible;
    }
</style>