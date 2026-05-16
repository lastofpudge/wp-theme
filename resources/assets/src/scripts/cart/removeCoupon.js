import Toast from '../libs/Toast'

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
        document.querySelectorAll('.js-total').forEach(el => { el.textContent = result.total })
        document.querySelectorAll('.js-sub-total').forEach(el => { el.textContent = result.subTotal })
        button.closest('li')?.remove()
        Toast.fire({ icon: 'success', iconColor: '#007cba', title: result.message })
      } else {
        Toast.fire({ icon: 'error', iconColor: 'red', title: result.message })
      }
    } catch (error) {
      console.error(error)
    }

    preloader?.classList.remove('js-preloading')
  })
}
