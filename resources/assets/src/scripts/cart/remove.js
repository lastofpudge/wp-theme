import Toast from '../libs/Toast'
import { syncCartUi } from './ui'

export function removeFromCart() {
  const preloader = document.querySelector('.js-preloader-main')
  const cartContainer = document.querySelector('body')

  cartContainer.addEventListener('click', async event => {
    const target = event.target.closest('.js-remove-from-cart')

    if (target) {
      preloader?.classList.add('js-preloading')

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

          syncCartUi(result)
          Toast.fire({ icon: 'success', iconColor: '#007cba', title: result.message })
          if (result.count === 0) {
            window.location.reload()
          }
        }
      } catch (error) {
        Toast.fire({ icon: 'error', iconColor: 'red', title: error.message || 'Request failed.' })
      }

      preloader?.classList.remove('js-preloading')
    }
  })
}
