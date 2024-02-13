import Toast from './libs/Toast'

export function initLoginForm() {
  const preloader = $('.js-preloader-main')
  $(document).on('submit', '.js-login-form', async function (event) {
    event.preventDefault()

    const form = $(this)
    const formData = new FormData()

    formData.append('email', form.find('input[name="email"]').val())
    formData.append('password', form.find('input[name="password"]').val())
    formData.append('action', 'login')
    formData.append('nonce', data.nonce)
    preloader.addClass('js-preloading')

    try {
      const { data: response } = await axios.post(data.ajax_url, formData, {
        headers: {
          'Content-Type': 'multipart/form-data'
        },
        params: {
          action: 'login',
          nonce: data.nonce
        }
      })

      if (response.type === 'success') {
        location.reload()
      } else {
        Toast.fire({ icon: 'error', iconColor: 'red', title: 'Error login' })
      }
    } catch (error) {
      console.log(error)
    }

    preloader.removeClass('js-preloading')
    form.trigger('reset')
  })
}
