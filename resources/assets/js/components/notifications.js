function popError(msg) {
  swal({
    type: 'error',
    title: 'Error',
    text: msg,
    showCancelButton: true,
    cancelButtonText: 'Close'
  });
}
