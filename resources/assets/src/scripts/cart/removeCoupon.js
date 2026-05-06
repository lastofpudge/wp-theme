import Toast from '../libs/Toast'

export function initRemoveCoupon() {
  const preloader = document.querySelector('.js-preloader-main')
  const couponButtons = document.querySelectorAll('.js-remove-coupon')

  if (couponButtons) {
    couponButtons.forEach(button => {
      button.addEventListener('click', async event => {
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

          if (result.response) {
            document.querySelectorAll('.js-total').forEach(el => { el.textContent = result.total })
            document.querySelectorAll('.js-sub-total').forEach(el => { el.textContent = result.subTotal })
            button.closest('li')?.remove()
            Toast.fire({ icon: 'success', iconColor: '#007cba', title: result.message || 'Success' })
          } else {
            Toast.fire({ icon: 'error', iconColor: 'red', title: result.message || 'Error' })
          }
        } catch (error) {
          console.error(error)
        }

        preloader?.classList.remove('js-preloading')
      })
    })
  }
}
