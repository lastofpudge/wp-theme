import Toast from './libs/Toast'

export function initRegisterForm() {
  const registerForm = document.querySelector('.js-register-form')

  if (!registerForm) return

  registerForm.addEventListener('submit', async event => {
    event.preventDefault()

    const formData = new FormData(registerForm)
    formData.append('action', 'register')
    formData.append('nonce', data.nonce)

    const preloader = document.querySelector('.js-preloader-main')
    preloader?.classList.add('js-preloading')

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
        Toast.fire({ icon: 'error', iconColor: 'red', title: result.message })
      }
    } catch (error) {
      Toast.fire({ icon: 'error', iconColor: 'red', title: error.message || 'Request failed.' })
    }

    preloader?.classList.remove('js-preloading')
    registerForm.reset()
  })
}
