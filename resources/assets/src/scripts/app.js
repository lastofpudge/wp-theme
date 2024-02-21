import axios from 'axios'

window.axios = axios

import { initContactForm } from './contact'
import { initLoginForm } from './login'
import { initCart } from './cart'

document.addEventListener('DOMContentLoaded', () => {
  initContactForm()
  initLoginForm()
  initCart()
})
