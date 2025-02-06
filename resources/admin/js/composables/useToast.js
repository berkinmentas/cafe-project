import Swal from 'sweetalert2'

export function useToast() {
  const success = (message) => {
    Swal.fire({
      icon: 'success',
      title: message,
      toast: true,
      position: 'top-end',
      showConfirmButton: false,
      timer: 3000
    })
  }

  const error = (message) => {
    Swal.fire({
      icon: 'error',
      title: message,
      toast: true,
      position: 'top-end',
      showConfirmButton: false,
      timer: 3000
    })
  }

  return {
    success,
    error
  }
} 