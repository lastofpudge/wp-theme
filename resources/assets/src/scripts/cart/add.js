import Toast from '../libs/Toast'
import { syncCartUi } from './ui'

export function addToCart() {
  const preloader = document.querySelector('.js-preloader-main')
  const addButtons = document.querySelectorAll('.js-add-to-cart')

  addButtons.forEach(addButton => {
    addButton.addEventListener('click', async () => {
      preloader?.classList.add('js-preloading')

      const formData = new FormData()
      formData.append('action', 'addToCart')
      formData.append('nonce', data.nonce)
      formData.append('product_id', addButton.dataset.product_id)
      const qtyInput = document.querySelector('.js-product-qty')
      formData.append('quantity', qtyInput ? qtyInput.value : addButton.dataset.quantity)
      if (addButton.dataset.variation) {
        formData.append('variation', addButton.dataset.variation)
      }

      try {
        const response = await fetch(data.ajax_url, { method: 'POST', body: formData })

        if (!response.ok) throw new Error(`Request failed with status ${response.status}`)

        const result = await response.json()

        if (result.type === 'success') {
          syncCartUi(result)
          Toast.fire({ icon: 'success', iconColor: '#007cba', title: result.message })
        }

        if (result.type === 'error') {
          Toast.fire({ icon: 'error', iconColor: 'red', title: result.message })
        }
      } catch (error) {
        Toast.fire({ icon: 'error', iconColor: 'red', title: error.message || 'Request failed.' })
      }

      preloader?.classList.remove('js-preloading')
    })
  })
}
