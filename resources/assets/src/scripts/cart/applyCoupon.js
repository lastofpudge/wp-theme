import Toast from '../libs/Toast'
import { syncCartUi } from './ui'

export function initApplyCoupon() {
  const preloader = document.querySelector('.js-preloader-main')
  const couponButton = document.querySelector('.js-apply-coupon')

  if (!couponButton) return

  couponButton.addEventListener('click', async () => {
    preloader?.classList.add('js-preloading')

    const couponCode = document.querySelector('.js-coupon').value

    const formData = new FormData()
    formData.append('action', 'applyCoupon')
    formData.append('nonce', data.nonce)
    formData.append('couponCode', couponCode)

    try {
      const response = await fetch(data.ajax_url, { method: 'POST', body: formData })

      if (!response.ok) throw new Error(`Request failed with status ${response.status}`)

      const result = await response.json()

      if (result.type === 'success') {
        syncCartUi(result)
        document.querySelector('.js-coupon').value = ''
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
