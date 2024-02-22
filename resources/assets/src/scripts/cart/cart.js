import { removeFromCart } from '@/cart/remove'
import { addToCart } from '@/cart/add'
import { changeVariations } from '@/cart/variations'

export function initCart() {
  removeFromCart()
  addToCart()
  changeVariations()
}
