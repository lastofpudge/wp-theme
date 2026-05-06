import noUiSlider from 'nouislider'

export function initRangeSlider() {
  const el = document.getElementById('rangeSlider')
  if (!el) return

  const rawMin = parseFloat(el.dataset.min)
  const rawMax = parseFloat(el.dataset.max)
  const valueEl = document.getElementById('slider-non-linear-step-value')

  if (!valueEl || Number.isNaN(rawMin) || Number.isNaN(rawMax)) return

  const absMin = Math.min(rawMin, rawMax)
  const absMax = Math.max(rawMin, rawMax)
  const requestedFrom = parseFloat(el.dataset.from)
  const requestedTo = parseFloat(el.dataset.to)
  const fromVal = Number.isNaN(requestedFrom) ? absMin : requestedFrom
  const toVal = Number.isNaN(requestedTo) ? absMax : requestedTo
  const startMin = Math.min(Math.max(fromVal, absMin), absMax)
  const startMax = Math.max(Math.min(toVal, absMax), startMin)

  const formatPrice = value => {
    const settings = data?.price_slider || {}
    const decimals = Number(settings.currency_format_num_decimals ?? 0)
    const decimalSeparator = settings.currency_format_decimal_sep ?? '.'
    const thousandSeparator = settings.currency_format_thousand_sep ?? ','
    const currencyFormat = settings.currency_format ?? '%1$s%2$s'
    const currencySymbol = settings.currency_symbol ?? ''
    const fixedValue = Number(value).toFixed(decimals)
    const [integerPart, fractionPart] = fixedValue.split('.')
    const formattedInteger = integerPart.replace(/\B(?=(\d{3})+(?!\d))/g, thousandSeparator)
    const formattedAmount = fractionPart
      ? `${formattedInteger}${decimalSeparator}${fractionPart}`
      : formattedInteger

    return currencyFormat
      .replace('%1$s', currencySymbol)
      .replace('%2$s', formattedAmount)
  }

  if (absMin === absMax) {
    el.hidden = true
    valueEl.textContent = formatPrice(absMin)
    return
  }

  el.hidden = false

  const slider = noUiSlider.create(el, {
    start: [startMin, startMax],
    connect: true,
    step: 1,
    range: { min: absMin, max: absMax },
    format: {
      to: v => Math.round(v),
      from: v => parseFloat(v),
    },
  })

  slider.on('update', values => {
    valueEl.textContent = `${formatPrice(values[0])} - ${formatPrice(values[1])}`
  })

  slider.on('set', values => {
    const urlParams = new URLSearchParams(window.location.search)
    urlParams.set('min_price', values[0])
    urlParams.set('max_price', values[1])
    urlParams.delete('paged')
    urlParams.delete('page')
    urlParams.delete('product-page')
    window.location.href = window.location.pathname + '?' + urlParams.toString()
  })
}
