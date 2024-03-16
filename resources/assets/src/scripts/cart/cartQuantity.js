import axios from 'axios'
import modalCartProduct from '@/cart/modalCartProduct.data'

export function iniCartQuantity() {
  const preloader = document.querySelector('.js-preloader-main')

  const totals = document.querySelectorAll('.js-total')
  const subTotals = document.querySelectorAll('.js-sub-total')
  const cartList = document.querySelector('.js-cart-list')
  const cartCount = document.querySelector('.js-cart-total')

  const changeButtons = document.querySelectorAll('.js-change-cart-quantity')

  if (changeButtons) {
    changeButtons.forEach(Button => {
      Button.addEventListener('click', async event => {
        preloader.classList.add('js-preloading')

        const key = Button.dataset.key
        const oldQuantity = parseInt(Button.dataset.quantity)

        const formData = new FormData()
        formData.append('action', 'updateCartQuantity')
        formData.append('nonce', data.nonce)
        formData.append('type', Button.dataset.action)
        formData.append('oldQuantity', oldQuantity)
        formData.append('key', key)

        try {
          const { data: response } = await axios.post(data.ajax_url, formData, {
            params: { action: 'updateCartQuantity' }
          })

          if (response.type === 'success') {
            const parentDiv = Button.parentElement
            const quantityInput = parentDiv.querySelector('input')

            const QuantityButtons = parentDiv.querySelectorAll('.js-change-cart-quantity')
            QuantityButtons.forEach(button => {
              button.dataset.quantity = response.newQuantity
            })

            quantityInput.value = response.newQuantity

            //update cart

            // totals
            totals.forEach(totalElement => {
              totalElement.innerHTML = response.total
            })

            // sub-totals
            subTotals.forEach(subTotalElement => {
              subTotalElement.innerHTML = response.subTotal
            })

            cartCount.innerHTML = response.count

            // cart items
            if (response.cart) {
              cartList.innerHTML = ''
              response.cart.forEach(product => {
                const newProduct = modalCartProduct(product)
                cartList.innerHTML += newProduct
              })
            }

            preloader.classList.remove('js-preloading')
          }
        } catch (error) {
          console.log(error)
          preloader.classList.remove('js-preloading')
        }
      })
    })
  }
}
