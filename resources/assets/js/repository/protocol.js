import eventBus from 'components/event-bus'
import axios from 'axios'

export default {
  protocol: {
    get(id) {
      return axios.get(`/protocols/${id}`)
        .then(response => {
          return response.data
        })
        .catch(error => {
          console.error(error.response.data)
          eventBus.$emit('info.snackbar', error.response.data.error)
        })
    },
    edit(id, payload) {
      return axios.put(`/protocols/${id}`, payload)
        .then(response => {
          if (response.status === 200) {
            eventBus.$emit('protocol.edited')
            eventBus.$emit('info.snackbar', `Protocol edited successfully!`)
          }
        })
        .catch(error => {
          console.error(error)
          eventBus.$emit('info.snackbar', error.response.data)
        })
    }
  }
}