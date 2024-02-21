import axios from 'axios'
import Toast from './libs/Toast'

export function initCart() {
  const preloader = document.querySelector('.js-preloader-main')
  const removeButtons = document.querySelectorAll('.js-remove-from-cart')
  const addButtons = document.querySelectorAll('.js-add-to-cart')

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

  addButtons.forEach(addButton => {
    addButton.addEventListener('click', async event => {
      preloader.classList.add('js-preloading')

      const formData = new FormData()
      formData.append('action', 'addToCart')
      formData.append('nonce', data.nonce)
      formData.append('product_id', addButton.dataset.product_id)
      formData.append('quantity', addButton.dataset.quantity)

      try {
        const { data: response } = await axios.post(data.ajax_url, formData, {
          params: { action: 'addToCart' }
        })

        if (response.type === 'success') {
          const total = document.querySelector('.js-total')
          const subTotal = document.querySelector('.js-sub-total')

          total.innerHTML = response.total
          subTotal.innerHTML = response.subTotal
          Toast.fire({ icon: 'success', iconColor: '#007cba', title: response.message })
        }

        if (response.type === 'error') {
          Toast.fire({ icon: 'error', iconColor: 'red', title: response.message })
        }

        preloader.classList.remove('js-preloading')
      } catch (error) {
        console.error(error)
      }
    })
  })
}
