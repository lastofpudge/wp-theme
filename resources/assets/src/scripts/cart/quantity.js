export function initQuantity() {
  const qtyButton = document.querySelector('.js-product-qty')
  const cartButton = document.querySelector('.js-add-to-cart')

  qtyButton.addEventListener('change', event => {
    cartButton.dataset.quantity = event.target.value
  })
}
