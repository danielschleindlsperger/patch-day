import moment from 'moment'

export default {
  filters: {
    Date(date) {
      return moment(date).format('DD.MM.YYYY')
    },
    ISODate(date) {
      return moment(date).format('YYYY-MM-DD')
    },
    checkIcon(value) {
      if (typeof(value) !== 'boolean') {
        console.warn('Type of value should be Boolean to avoid conversion' +
          ' errors')
      }
      // return icon based on truthiness
      return value ? 'check_circle' : 'close'
    },
    currency(value, currency, isCents) {
      // convert from cents if specified
      value = isCents ? value / 100 : value
      return value.toLocaleString('arab', {
        style: 'currency',
        currency,
        currencyDisplay: 'symbol',
      })
    }
  }
}