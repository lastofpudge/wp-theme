import axios from 'axios'

export function removeFromCart() {
  const preloader = document.querySelector('.js-preloader-main')
  const cartContainer = document.querySelector('.js-cart-list')

  const totals = document.querySelectorAll('.js-total')
  const subTotals = document.querySelectorAll('.js-sub-total')
  const cartCount = document.querySelector('.js-cart-total')

  cartContainer.addEventListener('click', async event => {
    const target = event.target

    if (target.classList.contains('js-remove-from-cart')) {
      preloader.classList.add('js-preloading')

      const formData = new FormData()
      formData.append('action', 'removeFromCart')
      formData.append('nonce', data.nonce)
      formData.append('key', target.dataset.key)

      try {
        const { data: response } = await axios.post(data.ajax_url, formData, {
          params: { action: 'removeFromCart' }
        })

        if (response.type === 'success') {
          const cartItems = cartContainer.querySelectorAll(`[data-key="${target.dataset.key}"]`)

          cartItems.forEach(cartItem => {
            cartItem.remove()
          })

          totals.forEach(totalElement => {
            totalElement.innerHTML = response.total
          })

          subTotals.forEach(subTotalElement => {
            subTotalElement.innerHTML = response.subTotal
          })

          cartCount.innerHTML = response.count
        }
        preloader.classList.remove('js-preloading')
      } catch (error) {
        console.error(error)
        preloader.classList.remove('js-preloading')
      }
    }
  })
}
