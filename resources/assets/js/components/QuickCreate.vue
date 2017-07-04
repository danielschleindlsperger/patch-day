<template>
    <v-card>
        <v-card-row class="primary">
            <v-card-title>
                <span class="white--text">Quick create</span>
            </v-card-title>
        </v-card-row>
        <v-card-text>
            <v-btn primary @click.native="createCompanyModal($event)"
                   class="white--text">Create Company</v-btn>

            <v-btn primary @click.native="createProjectModal($event)"
                   class="white--text">Create Project</v-btn>

            <v-btn primary @click.native="createUserModal($event)"
                   class="white--text">Create User</v-btn>
        </v-card-text>
        <create-company></create-company>
        <create-project></create-project>
        <create-user></create-user>
    </v-card>
</template>

<script>
  import eventBus from 'components/event-bus.js'
  import CreateCompany from 'pages/company/CreateCompany'
  import CreateProject from 'pages/project/modals/CreateProject'
  import CreateUser from 'pages/user/CreateUser'

  export default {
    name: 'quick-create',
    components: {
      CreateCompany,
      CreateProject,
      CreateUser,
    },
    methods: {
      createCompanyModal(event) {
        event.preventDefault()
        event.stopPropagation()
        eventBus.$emit('company.create.modal')
      },
      createProjectModal(event) {
        event.preventDefault()
        event.stopPropagation()
        eventBus.$emit('project.create.modal')
      },
      createUserModal(event) {
        event.preventDefault()
        event.stopPropagation()
        eventBus.$emit('user.create.modal')
      },
    },
  }
</script>