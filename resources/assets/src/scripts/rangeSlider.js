import noUiSlider from 'nouislider'

export function initRangeSlider() {
  const nonLinearStepSlider = document.getElementById('rangeSlider')

  if (nonLinearStepSlider) {
    noUiSlider.create(nonLinearStepSlider, {
      start: [20, 80],
      connect: true,
      range: {
        min: 0,
        max: 100
      }
    })

    const nonLinearStepSliderValueElement = document.getElementById('slider-non-linear-step-value')

    nonLinearStepSlider.noUiSlider.on('update', function (values) {
      nonLinearStepSliderValueElement.innerHTML = values.join(' - ')
    })
  }
}
