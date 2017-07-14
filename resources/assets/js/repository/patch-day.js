import eventBus from 'components/event-bus'
import axios from 'axios'

export default {
  patch_day:  {
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
    getAll() {
      return axios.get('/patch-days')
        .then(response => {
          return response.data
        })
        .catch(error => {
          console.error(error.response.data)
          eventBus.$emit('info.snackbar', error.response.data.error)
        })
    }
  }
}