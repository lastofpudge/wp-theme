import axios from 'axios'
import Toast from '@/libs/Toast'

export function initRemoveCoupon() {
  const preloader = document.querySelector('.js-preloader-main')

  const couponButtons = document.querySelectorAll('.js-remove-coupon')

  if (couponButtons) {
    couponButtons.forEach(button => {
      button.addEventListener('click', async event => {
        preloader.classList.add('js-preloading')

        const couponCode = button.dataset.coupon

        const formData = new FormData()
        formData.append('action', 'removeCoupon')
        formData.append('nonce', data.nonce)
        formData.append('couponCode', couponCode)

        try {
          const { data: result } = await axios.post(data.ajax_url, formData, {
            params: { action: 'removeCoupon' }
          })

          preloader.classList.remove('js-preloading')

          if (result.response) {
            Toast.fire({ icon: 'success', iconColor: '#007cba', title: 'Success' })
          } else {
            Toast.fire({ icon: 'error', iconColor: 'red', title: 'Error' })
          }
        } catch (error) {
          console.log(error)
          preloader.classList.remove('js-preloading')
        }
      })
    })
  }
}
