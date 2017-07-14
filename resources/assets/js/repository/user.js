import eventBus from 'components/event-bus'
import axios from 'axios'

export default {
  user: {
    get(id) {
      return axios.get(`/users/${id}`)
        .then(response => {
          return response.data
        })
        .catch(error => {
          console.error(error.response.data)
          eventBus.$emit('info.snackbar', error.response.data.error)
        })
    },
    getAll() {
      return axios.get('/users')
        .then(response => {
          return response.data
        })
        .catch(error => {
          console.error(error.response.data)
          eventBus.$emit('info.snackbar', error.response.data.error)
        })
    },
    create(payload) {
      return axios.post('/users', payload)
        .then(response => {
          if (response.status === 200) {
            eventBus.$emit('user.created')
            eventBus.$emit('info.snackbar',
              `User created successfully!`)
          }
        })
        .catch(error => {
          console.error(error)
          eventBus.$emit('info.snackbar', error.response.data)
        })
    },
    delete(id) {
      return axios.delete(`/users/${id}`)
        .then(response => {
          if (response.status === 200) {
            eventBus.$emit('user.deleted', id)
            eventBus.$emit('info.snackbar',
              `User deleted successfully!`)
          }
        })
        .catch(error => {
          console.error(error)
          eventBus.$emit('info.snackbar', error.response.data)
        })
    },
    edit(id, payload) {
      return axios.put(`/users/${id}`, payload)
        .then(response => {
          if (response.status === 200) {
            eventBus.$emit('user.edited')
            eventBus.$emit('info.snackbar', `User edited successfully!`)
          }
        })
        .catch(error => {
          console.error(error)
          eventBus.$emit('info.snackbar', error.response.data)
        })
    }
  }
}