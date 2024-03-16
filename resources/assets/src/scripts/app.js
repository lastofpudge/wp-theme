import axios from 'axios'

window.axios = axios

import { initContactForm } from '@/contact'
import { initLoginForm } from '@/login'
import { initCart } from '@/cart/cart'
import { initRangeSlider } from '@/rangeSlider'
import { iniCartQuantity } from '@/cart/cartQuantity'

document.addEventListener('DOMContentLoaded', () => {
  initContactForm()
  initLoginForm()
  initCart()
  iniCartQuantity()
  initRangeSlider()
})
