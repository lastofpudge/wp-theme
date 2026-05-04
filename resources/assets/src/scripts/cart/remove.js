export function removeFromCart() {
  const preloader = document.querySelector('.js-preloader-main')
  const cartContainer = document.querySelector('body')

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
        const response = await fetch(data.ajax_url, { method: 'POST', body: formData })

        if (!response.ok) throw new Error(`Request failed with status ${response.status}`)

        const result = await response.json()

        if (result.type === 'success') {
          const cartItems = cartContainer.querySelectorAll(`[data-key="${target.dataset.key}"]`)
          cartItems.forEach(cartItem => { cartItem.remove() })

          totals.forEach(el => { el.innerHTML = result.total })
          subTotals.forEach(el => { el.innerHTML = result.subTotal })
          cartCount.innerHTML = result.count
        }
      } catch (error) {
        console.error(error)
      }

      preloader.classList.remove('js-preloading')
    }
  })
}
