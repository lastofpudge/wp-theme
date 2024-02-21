import axios from 'axios'

export function initCart() {
  const removeButtons = document.querySelectorAll('.js-remove-form-cart')

  removeButtons.forEach(removeButton => {
    removeButton.addEventListener('click', async event => {
      const formData = new FormData()
      formData.append('action', 'removeFromCart')
      formData.append('nonce', data.nonce)
      formData.append('key', removeButton.dataset.key)

      try {
        const { data: response } = await axios.post(data.ajax_url, formData, {
          params: { action: 'removeFromCart' }
        })

        if (response.type === 'success') {
          const cartItem = event.target.closest('.js-cart-item')
          if (cartItem) {
            const total = document.querySelector('.js-total')
            const subTotal = document.querySelector('.js-sub-total')

            total.innerHTML = response.total
            subTotal.innerHTML = response.subTotal
            cartItem.remove()
          }
        }
      } catch (error) {
        console.error(error)
      }
    })
  })
}
