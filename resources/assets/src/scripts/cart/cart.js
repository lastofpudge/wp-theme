import { removeFromCart } from '@/cart/remove'
import { addToCart } from '@/cart/add'
import { changeVariations } from '@/cart/variations'
import { initQuantity } from '@/cart/quantity'
import { initApplyCoupon } from '@/cart/applyCoupon'
import { initRemoveCoupon } from '@/cart/removeCoupon'

export function initCart() {
  removeFromCart()
  addToCart()
  changeVariations()
  initQuantity()
  initApplyCoupon()
  initRemoveCoupon()
}
