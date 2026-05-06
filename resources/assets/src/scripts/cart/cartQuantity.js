import modalCartProduct from './modalCartProduct.data'

export function iniCartQuantity() {
  const preloader = document.querySelector('.js-preloader-main')

  const totals = document.querySelectorAll('.js-total')
  const subTotals = document.querySelectorAll('.js-sub-total')
  const cartList = document.querySelector('.js-cart-list')
  const cartCount = document.querySelector('.js-cart-total')

  const changeButtons = document.querySelectorAll('.js-change-cart-quantity')

  if (changeButtons) {
    changeButtons.forEach(button => {
      button.addEventListener('click', async event => {
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
            const parentDiv = button.parentElement
            const quantityInput = parentDiv.querySelector('input')

            const quantityButtons = parentDiv.querySelectorAll('.js-change-cart-quantity')
            quantityButtons.forEach(btn => { btn.dataset.quantity = result.newQuantity })

            quantityInput.value = result.newQuantity

            totals.forEach(el => { el.innerHTML = result.total })
            subTotals.forEach(el => { el.innerHTML = result.subTotal })
            if (cartCount) {
              cartCount.innerHTML = result.count
            }

            if (result.cart && cartList) {
              cartList.innerHTML = ''
              result.cart.forEach(product => {
                cartList.innerHTML += modalCartProduct(product)
              })
            }
          }
        } catch (error) {
          console.error(error)
        }

        preloader?.classList.remove('js-preloading')
      })
    })
  }
}
