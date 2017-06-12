c

<script>
  import eventBus from 'components/event-bus'
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
      this.getCompanies()

      eventBus.$on('user.edit.modal', user => {
        this.user = JSON.parse(JSON.stringify(user))
        this.isOpen = true
      })
    },
    methods: {
      editUser() {
        this.$http.put(`/users/${this.user.id}`, this.user)
          .then(response => {
            if (response.status === 200) {
              eventBus.$emit('user.edited', this.user)
              eventBus.$emit('info.snackbar',
                `User ${this.user.name} edited successfully!`)
              this.isOpen = false
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
    },
  }
</script>

<style lang="scss">
    .edit-user-modal .dialog {
        overflow-y: visible;
    }
</style>