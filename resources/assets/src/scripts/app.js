import axios from 'axios'

window.axios = axios

import { initContactForm } from './contact'
import { initLoginForm } from './login'

document.addEventListener('DOMContentLoaded', () => {
  initContactForm()
  initLoginForm()
})
