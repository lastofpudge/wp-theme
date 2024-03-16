import axios from 'axios'

window.axios = axios

import { initContactForm } from '@/contact'
import { initLoginForm } from '@/login'
import { initCart } from '@/cart/cart'
import { initRangeSlider } from '@/rangeSlider'

document.addEventListener('DOMContentLoaded', () => {
  initContactForm()
  initLoginForm()
  initCart()
  initRangeSlider()
})
