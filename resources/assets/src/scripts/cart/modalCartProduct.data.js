const modalCartProduct = product => {
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
                ${product.sale_price ? `${product.sale_price}` : `${product.regular_price}`}
            </td>
            
            <td class="text-end">
                <button type="button" data-key="${product.cart_item_key}" title="Remove" class="btn btn-danger js-remove-from-cart">
                    <i class="fa-solid fa-circle-xmark"></i>
                </button>
            </td>
        </tr>`
}

export default modalCartProduct