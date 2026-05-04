import Toast from './libs/Toast'

export function initContactForm() {
  const contactForm = document.querySelector('.js-contact-form')

  if (!contactForm) return
  contactForm.addEventListener('submit', async event => {
    event.preventDefault()

    const formData = new FormData(contactForm)
    formData.append('action', 'contact')
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

      const toastConfig = {
        icon: result.type,
        iconColor: result.type === 'success' ? '#007cba' : 'red',
        title: result.message
      }

      Toast.fire(toastConfig)
    } catch (error) {
      console.error(error)
    }

    preloader.classList.remove('js-preloading')
    contactForm.reset()
  })
}
