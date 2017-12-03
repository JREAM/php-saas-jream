// -----------------------------------------------------------------------------
// Document Ready
// -----------------------------------------------------------------------------
$(() => {
  // -----------------------------------------------------------------------------
  // Only Apply to proper Page
  // -----------------------------------------------------------------------------
  if (_.indexOf(['contact', 'support'], routes.current.controller == -1)) {
    return false;
  }

  // -----------------------------------------------------------------------------

  $('#form-contact').on('submit', function (evt) {
    evt.preventDefault();

    formUtils.disable(this.id);
    const url = $(this).attr('action');
    const postData = $(this).serialize();

    axios.post(url, postData).then((resp) => {
      swal({
        title: 'Email Dispatched',
        text: resp.msg,
        type: 'success',
        cancelButtonText: 'Close',
        timer: 2000
      }).then(
() => {},
        // Promise Rejection
        (dismiss) => {
          if (dismiss === 'timer') {
            window.location = '/contact/thanks';
          }
          window.location = '/contact/thanks';
      }
);
    }).catch(function (err) {
      alert(err.msg);
      // $(this).notify(err.msg, err.type);

      // Reset Recaptcha
      grecaptcha.reset();
      formUtils.enable(this.id);
    });
  });

  // -----------------------------------------------------------------------------
});
