import moment from 'moment'

export default {
  filters: {
    Date(date) {
      return moment(date).format('DD.MM.YYYY')
    },
    ISODate(date) {
      return moment(date).format('YYYY-MM-DD')
    },
    doneIcon(value) {
      if (typeof(value) !== 'boolean') {
        console.warn('Type of value should be Boolean to avoid conversion' +
          ' errors')
      }
      // return icon based on truthiness
      return value ? 'done' : 'radio_button_unchecked'
    },
  }
}