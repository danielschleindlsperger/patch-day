import moment from 'moment'

export default {
  filters: {
    Date(date) {
      return moment(date).format('DD.MM.YYYY')
    },
    DateTime(date) {
      return moment(date).format('DD.MM.YYYY k:mm')
    },
    ISODate(date) {
      return moment(date).format('YYYY-MM-DD')
    },
    HumanizeDate(date) {
      date = moment(date, 'YYYY-MM-DD')
      const now = moment()
      let diff = date.diff(now, 'days')

      if (diff === 0) {
        return 'today'
      } else {
        return moment().to(date)
      }
    },
    checkIcon(value) {
      if (typeof(value) !== 'boolean') {
        console.warn('Type of value should be Boolean to avoid conversion' +
          ' errors')
      }
      // return icon based on truthiness
      return value ? 'check_circle' : 'close'
    },
    currency(value, currency) {
      return value.toLocaleString('arab', {
        style: 'currency',
        currency,
        currencyDisplay: 'symbol',
      })
    }
  }
}