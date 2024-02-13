import axios from 'axios'
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
      const { data: response } = await axios.post(data.ajax_url, formData, {
        params: { action: 'contact' }
      })

      const toastConfig = {
        icon: response.type,
        iconColor: response.type === 'success' ? '#007cba' : 'red',
        title: response.message
      }

      Toast.fire(toastConfig)
    } catch (error) {
      console.error(error)
    }

    preloader.classList.remove('js-preloading')
    contactForm.reset()
  })
}
