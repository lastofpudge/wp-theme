import Toast from './libs/Toast'

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
      const response = await fetch(data.ajax_url, {
        method: 'POST',
        body: formData
      })

      if (!response.ok) {
        throw new Error(`Request failed with status ${response.status}`)
      }

      const result = await response.json()

      if (result.type === 'success') {
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
