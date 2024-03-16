import noUiSlider from 'nouislider'

export function initRangeSlider() {
  const nonLinearStepSlider = document.getElementById('rangeSlider')

  if (nonLinearStepSlider) {
    const nonLinearStepSliderValueElement = document.getElementById('slider-non-linear-step-value')

    const slider = noUiSlider.create(nonLinearStepSlider, {
      start: [0, 100],
      connect: true,
      range: {
        min: 0,
        max: 100
      }
    })

    nonLinearStepSlider.noUiSlider.on('update', function (values) {
      nonLinearStepSliderValueElement.innerHTML = values.join(' - ')
    })

    const valueElement = document.getElementById('slider-non-linear-step-value')
    const urlParams = new URLSearchParams(window.location.search)

    const setSliderValues = values => {
      slider.set(values)
      valueElement.innerHTML = values.join(' - ')
    }

    const minPrice = parseFloat(urlParams.get('min_price')) || 0
    const maxPrice = parseFloat(urlParams.get('max_price')) || 100
    setSliderValues([minPrice, maxPrice])

    slider.on('set', values => {
      const newUrlParams = new URLSearchParams(urlParams)
      newUrlParams.set('min_price', values[0])
      newUrlParams.set('max_price', values[1])

      const newUrl = window.location.pathname + '?' + newUrlParams.toString()
      history.pushState({}, '', newUrl)
      location.reload()
    })
  }
}
