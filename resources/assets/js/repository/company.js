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
      let data = new FormData()
      if (payload.logo) {
        data.append('logo', payload.logo)
      }
      data.append('name', payload.name)

      return axios.post('/companies', data)
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
      let data = new FormData()
      if (payload.logo) {
        data.append('logo', payload.logo, payload.logo.name)
      }
      data.append('name', payload.name)

      // can't send files with PUT method, so fake it with POST
      data.append('_method', 'PUT')

      return axios.post(`/companies/${id}`, data)
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