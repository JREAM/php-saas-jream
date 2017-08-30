// -----------------------------------------------------------------------------
// Document Ready
// -----------------------------------------------------------------------------
$(() => {

  // -----------------------------------------------------------------------------

  $("#formNewsletterSubscribe").submit(function (evt) {
    evt.preventDefault();

    const url = $(this).attr("action");
    const postData = $(this).serialize();

    axios.post(url, postData).then(resp => {
      swal({
        title: 'Success',
        text: 'Your email has been registered, please verify you email address in your inbox!',
        type: 'success',
        timer: 3000
      })
    })
    .catch(err => {
      popError(err.msg);
    });

  });

  // -----------------------------------------------------------------------------

  $("#formNewsletterVerify").submit(function (evt) {
    evt.preventDefault();

    const url = $(this).attr("action");
    const postData = $(this).serialize();

    axios.post(url, postData).then(resp => {
      swal({
        title: 'Success',
        text: 'Your email has been validated',
        type: 'success',
        timer: 3000
      })
    })
    .catch(err => {
      popError(err.msg);
    });

  });

  // -----------------------------------------------------------------------------

  $("#formNewsletterUnSubscribe").submit(function (evt) {
    evt.preventDefault();

    const url = $(this).attr("action");
    const postData = $(this).serialize();

    axios.post(url, postData).then(resp => {
      swal({
        title: 'Success',
        text: 'Your email has been unsubscribed from future newsletters!',
        type: 'success',
        timer: 3000
      })
    })
    .catch(err => {
      popError(err.msg);
    });

  });

  // -----------------------------------------------------------------------------

});
