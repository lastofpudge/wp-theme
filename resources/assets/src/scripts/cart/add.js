import axios from 'axios'
import Toast from '@/libs/Toast'

export function addToCart() {
  const preloader = document.querySelector('.js-preloader-main')
  const addButtons = document.querySelectorAll('.js-add-to-cart')

  const totals = document.querySelectorAll('.js-total')
  const subTotals = document.querySelectorAll('.js-sub-total')
  const cartCount = document.querySelector('.js-cart-total')
  const addedProduct = product => {
    return `
        <tr class="js-cart-item" data-ajax="true" data-key="${product.cart_item_key}">
            <td class="text-center">
                <a href="${product.link}" class="d-block thumb-sm">
                    ${product.thumbnail}
                </a>
            </td>
            <td class="text-start">
                <a href="${product.link}">${product.name}</a>
            </td>
           
            <td>x${product.quantity}</td>
            <td class="text-end">
                ${
                  product.sale_price
                    ? `<s><bdi>${product.regular_price}&nbsp;<span class="woocommerce-Price-currencySymbol">${product.currency_symbol}</span></bdi></s> ${product.sale_price} ${product.currency_symbol}`
                    : `${product.regular_price}&nbsp;<span class="woocommerce-Price-currencySymbol">${product.currency_symbol}</span>`
                }
            </td>
            
            <td class="text-end">
                <button type="button" data-key="${product.cart_item_key}" title="Remove" class="btn btn-danger js-remove-from-cart">
                    <i class="fa-solid fa-circle-xmark"></i>
                </button>
            </td>
        </tr>`
  }
  addButtons.forEach(addButton => {
    addButton.addEventListener('click', async event => {
      preloader.classList.add('js-preloading')

      const formData = new FormData()
      formData.append('action', 'addToCart')
      formData.append('nonce', data.nonce)
      formData.append('product_id', addButton.dataset.product_id)
      formData.append('quantity', addButton.dataset.quantity)
      if (addButton.dataset.variation) {
        formData.append('variation', addButton.dataset.variation)
      }

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

          if (response.product) {
            const newProduct = addedProduct(response.product)
            const cartList = document.querySelector('.js-cart-list')
            cartList.innerHTML += newProduct
          }

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
