// -----------------------------------------------------------------------------
// Shorthand Error Alert
// -----------------------------------------------------------------------------

window.popError = function(msg) {
  swal({
    type: 'error',
    title: 'Error',
    text: msg,
    showConfirmButton: false,
    showCancelButton: true,
    cancelButtonText: 'Close'
  });
}

// -----------------------------------------------------------------------------
