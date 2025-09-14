import Swal from 'sweetalert2/dist/sweetalert2.js'

export default Swal.mixin({
  toast: true,
  position: 'top-right',
  showClass: { backdrop: 'swal2-noanimation', popup: '', icon: '' },
  showConfirmButton: false,
  timer: 2000,
  timerProgressBar: true
})
