import Toast from '../libs/Toast'
import { syncCartUi } from './ui'

export function initRemoveCoupon() {
  const preloader = document.querySelector('.js-preloader-main')

  document.body.addEventListener('click', async event => {
    const button = event.target.closest('.js-remove-coupon')
    if (!button) return

    preloader?.classList.add('js-preloading')

    const couponCode = button.dataset.coupon

    const formData = new FormData()
    formData.append('action', 'removeCoupon')
    formData.append('nonce', data.nonce)
    formData.append('couponCode', couponCode)

    try {
      const response = await fetch(data.ajax_url, { method: 'POST', body: formData })

      if (!response.ok) throw new Error(`Request failed with status ${response.status}`)

      const result = await response.json()

      if (result.type === 'success') {
        syncCartUi(result)
        Toast.fire({ icon: 'success', iconColor: '#007cba', title: result.message })
      } else {
        Toast.fire({ icon: 'error', iconColor: 'red', title: result.message })
      }
    } catch (error) {
      Toast.fire({ icon: 'error', iconColor: 'red', title: error.message || 'Request failed.' })
    }

    preloader?.classList.remove('js-preloading')
  })
}
