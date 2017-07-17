export default {
  methods: {
    tooltipVisible(tooltip) {
      return typeof tooltip === 'string'
    },
    tooltipHtml(tooltip) {
      return typeof tooltip === 'string' ? tooltip : ''
    }
  }
}