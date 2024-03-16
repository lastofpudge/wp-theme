export function changeVariations() {
  const select = document.querySelector('.js-variant')

  if (select) {
    const addButton = document.querySelector('.js-add-to-cart')
    select.addEventListener('change', event => {
      addButton.dataset.variation = event.target.value
      addButton.removeAttribute('disabled')
    })
  }
}
