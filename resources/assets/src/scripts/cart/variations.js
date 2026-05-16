export function changeVariations() {
  const select = document.querySelector('.js-variant')

  if (!select) return

  const addButton = document.querySelector('.js-add-to-cart')
  const priceEl = document.querySelector('.js-product-price')

  select.addEventListener('change', event => {
    const id = event.target.value
    addButton.dataset.variation = id
    addButton.removeAttribute('disabled')

    if (priceEl) {
      const source = document.querySelector(`.js-variation-price[data-id="${id}"]`)
      if (source) {
        priceEl.replaceChildren(...Array.from(source.childNodes).map(n => n.cloneNode(true)))
      }
    }
  })
}
