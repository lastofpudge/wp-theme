import { removeFromCart } from './remove'
import { addToCart } from './add'
import { changeVariations } from './variations'
import { initQuantity } from './quantity'
import { initApplyCoupon } from './applyCoupon'
import { initRemoveCoupon } from './removeCoupon'

export function initCart() {
  removeFromCart()
  addToCart()
  changeVariations()
  initQuantity()
  initApplyCoupon()
  initRemoveCoupon()
}
