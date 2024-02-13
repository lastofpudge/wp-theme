import Toast from './libs/Toast'
import axios from 'axios'

export function initLoginForm() {
  const loginForm = document.querySelector('.js-login-form')

  if (!loginForm) return
  loginForm.addEventListener('submit', async event => {
    event.preventDefault()

    const formData = new FormData(loginForm)
    formData.append('action', 'login')
    formData.append('nonce', data.nonce)

    const preloader = document.querySelector('.js-preloader-main')
    preloader.classList.add('js-preloading')

    try {
      const { data: response } = await axios.post(data.ajax_url, formData, {
        params: { action: 'login' }
      })

      if (response.type === 'success') {
        location.reload()
      } else {
        Toast.fire({ icon: 'error', iconColor: 'red', title: 'Error login' })
      }
    } catch (error) {
      console.error(error)
    }

    preloader.classList.remove('js-preloading')
    loginForm.reset()
  })
}
