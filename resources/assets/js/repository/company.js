import eventBus from 'components/event-bus'
import axios from 'axios'

export default {
  company: {
    get(id) {
      return axios.get(`/companies/${id}`)
        .then(response => {
          return response.data
        })
        .catch(error => {
          console.error(error.response.data)
          eventBus.$emit('info.snackbar', error.response.data.error)
        })
    },
    getAll() {
      return axios.get('/companies')
        .then(response => {
          return response.data
        })
        .catch(error => {
          console.error(error.response.data)
          eventBus.$emit('info.snackbar', error.response.data.error)
        })
    },
    create(payload) {
      return axios.post('/companies', payload)
        .then(response => {
          if (response.status === 200) {
            eventBus.$emit('company.created')
            eventBus.$emit('info.snackbar',
              `Company created successfully!`)
          }
        })
        .catch(error => {
          console.error(error)
          eventBus.$emit('info.snackbar', error.response.data)
        })
    },
    delete(id) {
      return axios.delete(`/companies/${id}`)
        .then(response => {
          if (response.status === 200) {
            eventBus.$emit('company.deleted', id)
            eventBus.$emit('info.snackbar',
              `Company deleted successfully!`)
          }
        })
        .catch(error => {
          console.error(error)
          eventBus.$emit('info.snackbar', error.response.data)
        })
    },
    edit(id, payload) {
      return axios.put(`/companies/${id}`, payload)
        .then(response => {
          if (response.status === 200) {
            eventBus.$emit('company.edited')
            eventBus.$emit('info.snackbar', `Company edited successfully!`)
          }
        })
        .catch(error => {
          console.error(error)
          eventBus.$emit('info.snackbar', error.response.data)
        })
    }
  }
}