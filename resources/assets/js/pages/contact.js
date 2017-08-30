// -----------------------------------------------------------------------------
// Document Ready
// -----------------------------------------------------------------------------
$(() => {

  // -----------------------------------------------------------------------------

  $("#formContact").on('submit', function (evt) {
    evt.preventDefault();

    formUtils.disable(this.id);
    let url = $(this).attr('action');
    let postData = $(this).serialize();

    axios.post(url, postData).then(resp => {
      swal({
        title: 'Email Dispatched',
        text: resp.msg,
        type: 'success',
        cancelButtonText: 'Close',
        timer: 2000
      }).then(function () {},
        // Promise Rejection
        function (dismiss) {
          if (dismiss === 'timer') {
            window.location = '/contact/thanks';
          }
          window.location = '/contact/thanks';
      });

    }).catch(function (err) {

      popError(err.msg)

      // Reset Recaptcha
      grecaptcha.reset();
      formUtils.enable(this.id);
    })

  });

  // -----------------------------------------------------------------------------

});
