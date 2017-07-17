import eventBus from 'components/event-bus'
import axios from 'axios'

export default {
  technology: {
    getAll() {
      return axios.get('/technologies')
        .then(response => {
          return response.data
        })
        .catch(error => {
          console.error(error.response.data)
          eventBus.$emit('info.snackbar', error.response.data.error)
        })
    },
    create(payload) {
      return axios.post('/technologies', payload)
        .then(response => {
          if (response.status === 200) {
            eventBus.$emit('technology.created')
            eventBus.$emit('info.snackbar',
              `Technology created successfully!`)
          }
        })
        .catch(error => {
          console.error(error)
          eventBus.$emit('info.snackbar', error.response.data)
        })
    },
  }
}