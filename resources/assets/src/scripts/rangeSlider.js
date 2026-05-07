import noUiSlider from 'nouislider'

export function initRangeSlider() {
  const el = document.getElementById('rangeSlider')
  if (!el) return

  const absMin = parseFloat(el.dataset.min)
  const absMax = parseFloat(el.dataset.max)
  const valueEl = document.getElementById('slider-non-linear-step-value')

  if (!valueEl || Number.isNaN(absMin) || Number.isNaN(absMax)) return

  const formatPrice = value => {
    const s = data?.price_slider ?? {}
    const decimals = Number(s.currency_format_num_decimals ?? 0)
    const [int, frac] = Number(value).toFixed(decimals).split('.')
    const amount = int.replace(/\B(?=(\d{3})+(?!\d))/g, s.currency_format_thousand_sep ?? ',')
      + (frac ? (s.currency_format_decimal_sep ?? '.') + frac : '')
    return (s.currency_format ?? '%1$s%2$s')
      .replace('%1$s', s.currency_symbol ?? '')
      .replace('%2$s', amount)
  }

  if (absMin === absMax) {
    el.hidden = true
    valueEl.textContent = formatPrice(absMin)
    return
  }

  const fromVal = parseFloat(el.dataset.from)
  const toVal = parseFloat(el.dataset.to)
  const startMin = Number.isNaN(fromVal) ? absMin : Math.min(Math.max(fromVal, absMin), absMax)
  const startMax = Number.isNaN(toVal) ? absMax : Math.max(Math.min(toVal, absMax), startMin)

  const slider = noUiSlider.create(el, {
    start: [startMin, startMax],
    connect: true,
    step: 1,
    range: { min: absMin, max: absMax },
    format: { to: v => Math.round(v), from: v => parseFloat(v) },
  })

  slider.on('update', values => {
    valueEl.textContent = `${formatPrice(values[0])} - ${formatPrice(values[1])}`
  })

  slider.on('set', values => {
    const params = new URLSearchParams(window.location.search)
    params.set('min_price', values[0])
    params.set('max_price', values[1])
    params.delete('paged')
    params.delete('page')
    params.delete('product-page')
    window.location.href = window.location.pathname + '?' + params.toString()
  })
}
