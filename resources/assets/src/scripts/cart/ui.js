import modalCartProduct from './modalCartProduct.data'

function updateHtml(selector, html) {
  document.querySelectorAll(selector).forEach(element => {
    element.innerHTML = html
  })
}

function updateText(selector, value) {
  document.querySelectorAll(selector).forEach(element => {
    element.textContent = String(value)
  })
}

function toggleHeaderBadge(count) {
  document.querySelectorAll('.js-cart-count-badge').forEach(badge => {
    badge.textContent = String(count)
    badge.classList.toggle('d-none', count < 1)
  })
}

function updateMiniCart(cartItems) {
  const html = cartItems.map(product => modalCartProduct(product)).join('')

  document.querySelectorAll('.js-cart-list').forEach(list => {
    list.innerHTML = html
  })
}

function updateCartRows(items) {
  Object.entries(items).forEach(([key, item]) => {
    const row = document.querySelector(`.js-cart-row[data-key="${key}"]`)
    if (!row) return

    const quantityInput = row.querySelector('.js-cart-qty-input')
    if (quantityInput) {
      quantityInput.value = item.quantity
    }

    row.querySelectorAll('.js-change-cart-quantity').forEach(button => {
      button.dataset.quantity = item.quantity
    })

    const linePrice = row.querySelector('.js-cart-line-price')
    if (linePrice) {
      linePrice.innerHTML = item.price_html
    }

    const lineSubtotal = row.querySelector('.js-cart-line-subtotal')
    if (lineSubtotal) {
      lineSubtotal.innerHTML = item.line_subtotal_html
    }
  })
}

function updateCoupons(response) {
  const couponList = document.querySelector('.js-cart-coupon-list')
  if (couponList) {
    couponList.innerHTML = response.couponListHtml || ''
  }

  const discountRow = document.querySelector('.js-cart-discount-row')
  const discountHtml = response.discountRowHtml || ''

  if (discountRow) {
    if (discountHtml) {
      discountRow.outerHTML = discountHtml
    } else {
      discountRow.remove()
    }
  } else if (discountHtml) {
    const totalRow = document.querySelector('#checkout-total tr:last-child')
    totalRow?.insertAdjacentHTML('beforebegin', discountHtml)
  }
}

export function syncCartUi(response) {
  updateHtml('.js-total', response.total)
  updateHtml('.js-sub-total', response.subTotal)
  updateText('.js-cart-total', response.count)
  updateText('.js-cart-count-label', response.countLabel)
  toggleHeaderBadge(response.count)
  updateMiniCart(response.cart || [])
  updateCartRows(response.items || {})
  updateCoupons(response)
}
