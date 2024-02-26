import { removeFromCart } from '@/cart/remove'
import { addToCart } from '@/cart/add'
import { changeVariations } from '@/cart/variations'
import { initQuantity } from '@/cart/quantity'

export function initCart() {
  removeFromCart()
  addToCart()
  changeVariations()
  initQuantity()
}
