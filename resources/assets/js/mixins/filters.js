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
    DaysFromNow(date) {
      date = moment(date, 'YYYY-MM-DD')
      const now = moment()
      let diff = date.diff(now, 'days')
      let str = '';

      if (diff < 0) {
        str = `${-diff} days ago`
      } else if (diff === 0) {
        str = 'today'
      } else {
        str = `${diff} days from now`
      }
      return str
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