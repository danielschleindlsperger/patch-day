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
  }
}