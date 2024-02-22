import axios from 'axios'
import Toast from './libs/Toast'

export function initCart() {
  const preloader = document.querySelector('.js-preloader-main')
  const removeButtons = document.querySelectorAll('.js-remove-from-cart')
  const addButtons = document.querySelectorAll('.js-add-to-cart')

  const totals = document.querySelectorAll('.js-total')
  const subTotals = document.querySelectorAll('.js-sub-total')

  const cartCount = document.querySelector('.js-cart-total')

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
            cartItem.remove()
          }

          totals.forEach(totalElement => {
            totalElement.innerHTML = response.total
          })

          subTotals.forEach(subTotalElement => {
            subTotalElement.innerHTML = response.subTotal
          })

          cartCount.innerHTML = response.count
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
          totals.forEach(totalElement => {
            totalElement.innerHTML = response.total
          })

          subTotals.forEach(subTotalElement => {
            subTotalElement.innerHTML = response.subTotal
          })

          cartCount.innerHTML = response.count
          
          Toast.fire({ icon: 'success', iconColor: '#007cba', title: response.message })
        }

        if (response.type === 'error') {
          Toast.fire({ icon: 'error', iconColor: 'red', title: response.message })
        }

        preloader.classList.remove('js-preloading')
      } catch (error) {
        preloader.classList.remove('js-preloading')
        console.error(error)
      }
    })
  })
}
