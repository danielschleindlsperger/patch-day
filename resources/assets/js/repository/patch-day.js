import eventBus from 'components/event-bus'
import axios from 'axios'

export default {
  patch_day: {
    get(id) {
      return axios.get(`/patch-days/${id}`)
        .then(response => {
          return response.data
        })
        .catch(error => {
          console.error(error.response.data)
          eventBus.$emit('info.snackbar', error.response.data.error)
        })
    },
    getTodo(id) {
      return axios.get(`/patch-days/${id}?todo=true`)
        .then(response => {
          return response.data
        })
        .catch(error => {
          console.error(error.response.data)
          eventBus.$emit('info.snackbar', error.response.data.error)
        })
    },
    getAll() {
      return axios.get('/patch-days')
        .then(response => {
          return response.data
        })
        .catch(error => {
          console.error(error.response.data)
          eventBus.$emit('info.snackbar', error.response.data.error)
        })
    },
    create(payload) {
      return axios.post('/patch-days', payload)
        .then(response => {
          if (response.status === 200) {
            eventBus.$emit('patch_day.created')
            eventBus.$emit('info.snackbar',
              `PatchDay created successfully!`)
          }
        })
        .catch(error => {
          console.error(error)
          eventBus.$emit('info.snackbar', error.response.data)
        })
    },
    delete(id) {
      return axios.delete(`/patch-days/${id}`)
        .then(response => {
          if (response.status === 200) {
            eventBus.$emit('patch_day.deleted', id)
            eventBus.$emit('info.snackbar',
              `PatchDay deleted successfully!`)
          }
        })
        .catch(error => {
          console.error(error)
          eventBus.$emit('info.snackbar', error.response.data)
        })
    },
    edit(id, payload) {
      return axios.put(`/patch-days/${id}`, payload)
        .then(response => {
          if (response.status === 200) {
            eventBus.$emit('patch_day.edited')
            eventBus.$emit('info.snackbar', `PatchDay edited successfully!`)
          }
        })
        .catch(error => {
          console.error(error)
          eventBus.$emit('info.snackbar', error.response.data)
        })
    }
  }
}