import { initContactForm } from './contact'
import { initLoginForm } from './login'
import { initCart } from './cart/cart'
import { initRangeSlider } from './rangeSlider'
import { initCartQuantity } from './cart/cartQuantity'
import { initRegisterForm } from './register'

document.addEventListener('DOMContentLoaded', () => {
  initContactForm()
  initLoginForm()
  initRegisterForm()
  initCart()
  initCartQuantity()
  initRangeSlider()
})
