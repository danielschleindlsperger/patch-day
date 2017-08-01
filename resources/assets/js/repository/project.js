import eventBus from 'components/event-bus'
import axios from 'axios'

export default {
  project: {
    get(id) {
      return axios.get(`/projects/${id}`)
        .then(response => {
          return response.data
        })
        .catch(error => {
          console.error(error.response.data)
          eventBus.$emit('info.snackbar', error.response.data.error)
        })
    },
    getAll() {
      return axios.get('/projects')
        .then(response => {
          return response.data
        })
        .catch(error => {
          console.error(error.response.data)
          eventBus.$emit('info.snackbar', error.response.data.error)
        })
    },
    create(payload) {
      return axios.post('/projects', payload)
        .then(response => {
          if (response.status === 200) {
            eventBus.$emit('project.created')
            eventBus.$emit('info.snackbar',
              `Project created successfully!`)
          }
        })
        .catch(error => {
          console.error(error)
          eventBus.$emit('info.snackbar', error.response.data)
        })
    },
    delete(id) {
      return axios.delete(`/projects/${id}`)
        .then(response => {
          if (response.status === 200) {
            eventBus.$emit('project.deleted', id)
            eventBus.$emit('info.snackbar',
              `Project deleted successfully!`)
          }
        })
        .catch(error => {
          console.error(error)
          eventBus.$emit('info.snackbar', error.response.data)
        })
    },
    edit(id, payload) {
      return axios.put(`/projects/${id}`, payload)
        .then(response => {
          if (response.status === 200) {
            eventBus.$emit('project.edited', id)
            eventBus.$emit('info.snackbar', `Project edited successfully!`)
          }
        })
        .catch(error => {
          console.error(error)
          eventBus.$emit('info.snackbar', error.response.data)
        })
    },
    cancelPatchDay(project, patch_day) {
      return axios.delete(`/projects/${project.id}/cancel`, {
        data: {
          patch_day_id: patch_day.id
        }
      }).then(response => {
        eventBus.$emit('info.snackbar',
          `Successfully cancelled ${patch_day.name}!`)
        eventBus.$emit('patch_day.cancelled')
      })
        .catch(error => {
          console.error(error)
          eventBus.$emit('info.snackbar', error.response.data.error)
        })
    },
    signUpForPatchDay(project, patch_day) {
      return axios.post(`/projects/${project.id}/signup`, {
        patch_day_id: patch_day.id
      })
        .then(response => {
          eventBus.$emit('info.snackbar',
            `Successfully signed up for ${patch_day.name}!`)
          eventBus.$emit('patch_day.signed_up')

          this.isOpen = false
        })
        .catch(error => {
          console.error(error)
          eventBus.$emit('info.snackbar', error.response.data.error)
        })
    },
    getRegisteredPatchDays(project) {
      return axios.get(`projects/${project.id}/registered-patch-days`)
        .then(response => {
          return response.data
        })
        .catch(error => {
          console.error(error.response.data)
          eventBus.$emit('info.snackbar', error.response.data.error)
        })
    },
    getPossibleSignups(project) {
      return axios.get(`projects/${project.id}/signup`)
        .then(response => {
          return response.data
        })
        .catch(error => {
          console.error(error.response.data)
          eventBus.$emit('info.snackbar', error.response.data.error)
        })
    },
    deleteTechnology(project, technology) {
      return axios.delete(`projects/${project.id}/delete-technology`, {
        data: {
          tech: technology.id
        }
      })
        .then(response => {
          eventBus.$emit('info.snackbar', `Successfully deleted ${technology.name} from ${project.name}!`)
          eventBus.$emit('technology.deleted', technology)
          return response.data
        })
        .catch(error => {
          console.error(error.response.data)
          eventBus.$emit('info.snackbar', error.response.data.error)
        })
    }
  }
}