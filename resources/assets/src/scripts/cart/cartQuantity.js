import Toast from '../libs/Toast'
import { syncCartUi } from './ui'

export function initCartQuantity() {
  const preloader = document.querySelector('.js-preloader-main')

  const changeButtons = document.querySelectorAll('.js-change-cart-quantity')

  if (changeButtons) {
    changeButtons.forEach(button => {
      button.addEventListener('click', async () => {
        preloader?.classList.add('js-preloading')

        const key = button.dataset.key
        const oldQuantity = parseInt(button.dataset.quantity)

        const formData = new FormData()
        formData.append('action', 'updateCartQuantity')
        formData.append('nonce', data.nonce)
        formData.append('type', button.dataset.action)
        formData.append('oldQuantity', oldQuantity)
        formData.append('key', key)

        try {
          const response = await fetch(data.ajax_url, { method: 'POST', body: formData })

          if (!response.ok) throw new Error(`Request failed with status ${response.status}`)

          const result = await response.json()

          if (result.type === 'success') {
            syncCartUi(result)
          }
        } catch (error) {
          Toast.fire({ icon: 'error', iconColor: 'red', title: error.message || 'Request failed.' })
        }

        preloader?.classList.remove('js-preloading')
      })
    })
  }
}
