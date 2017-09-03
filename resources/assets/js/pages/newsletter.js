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
      $(this).notify('Your email has been registered, please verify you email address in your inbox!', "success");
    })
    .catch(err => {
      $(this).notify(err.msg, "error");
    });

  });

  // -----------------------------------------------------------------------------

  $("#formNewsletterVerify").submit(function (evt) {
    evt.preventDefault();

    const url = $(this).attr("action");
    const postData = $(this).serialize();

    axios.post(url, postData).then(resp => {
      $(this).notify('Your email has been validated', "success");
    })
    .catch(err => {
      $(this).notify(err.msg, "error");
    });

  });

  // -----------------------------------------------------------------------------

  $("#formNewsletterUnsubscribe").submit(function (evt) {
    evt.preventDefault();

    const url = $(this).attr("action");
    const postData = $(this).serialize();

    axios.post(url, postData).then(resp => {
      $(this).notify('Your email has been unsubscribed from future newsletters!', "success");
    })
    .catch(err => {
      $(this).notify(err.msg, "error");
    });

  });

  // -----------------------------------------------------------------------------

});
